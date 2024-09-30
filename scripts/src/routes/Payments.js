const express = require("express");
const router = express.Router();
require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET);
var _ = require("lodash");
var { report } = require("../set.js");
const queries = require("../mysql/queries.js");
const moment = require("moment");

router.get("/", (req, res) => {
  res.send("Payments page");
});

/* Workers */

// create a connected account | returns account id
router.post("/create", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.body.userId || !req.body.email) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  let account;

  try {
    // Create the stripe connected account
    account = await stripe.accounts.create({
      type: "express",
      country: "US",
      email: req.body.email,
    });

    await queries.insertStripeId(account.id, req.body.userId);

    res.status(200).json({ status: true, message: account.id });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// get - account links | consumes stripeId
router.get("/account-link", async (req, res) => {
  if (!Object.keys(req.query).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.query.stripeId || !req.query.userId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  let accountLink;

  try {
    await queries.checkStripeId(req.body.userId);

    accountLink = await stripe.accountLinks.create({
      account: req.query.stripeId,
      refresh_url: process.env.REFRESH_URL_BASE_PATH + "/" + req.query.stripeId,
      return_url: process.env.RETURN_URL_BASE_PATH + "/worker/dashboard",
      type: "account_onboarding",
    });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }

  // the expiracy is 5 minutes
  return res.status(200).json({ status: true, message: accountLink.url });
});

// get - login links | consumes stripeId
router.get("/login-link", async (req, res) => {
  if (!Object.keys(req.query).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.body.stripeId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  let loginLink;

  try {
    await queries.checkStripeId(req.body.userId);
    loginLink = await stripe.accounts.createLoginLink(req.query.stripeId);
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }

  return res.status(200).json({ status: true, message: loginLink.url });
});

// post - transfer
// creates a transfer | returns true or false
router.post("/transfer", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.body.accountId || !req.body.amount) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  try {
    let stripeId = query.getStripeId(accountId);

    // Create the transfer
    const account = await stripe.transfers.create({
      amount: Number(req.body.amount) * 100,
      currency: "usd",
      destination: stripeId,
    });

    // DB save worker payment
    res.status(200).json({ status: true, message: "OK" });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

/* Recruiters - Organizations (orgs) */

// Create a customer
router.post("/customer/create", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.body.email) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameter." });
  }

  // Check if the customer exists
  try {
    const customerTest = await stripe.customers.list({
      email: req.body.email,
    });

    if (customerTest.data.length > 0) {
      return res
        .status(400)
        .send({ status: false, message: "Client exists.", code: 101 });
    }
  } catch (e) {
    console.log(e.message);
    return res.status(400).send({ status: false, message: e.message });
  }

  const portal = "https://billing.stripe.com/p/login/test_8wMaFa19ddXy1wc5kk";

  // Create the customer
  try {
    const customer = await stripe.customers.create({
      email: req.body.email,
      payment_method: "pm_card_visa",
      invoice_settings: {
        default_payment_method: "pm_card_visa",
      },
    });

    await queries.insertCustomerStripeId(customer.id, req.body.email);

    res.status(200).json({ status: true, message: portal });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// Create an invoice
router.post("/customer/invoice", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.body.stripeId || !req.body.amount || !req.body.offerId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  try {
    // first set the offer on hold
    try {
      // first set the offer on hold
      await queries.setOfferStatus(req.body.offerId, "Hold", "1", "0");
    } catch (error) {
      console.error("Error setting offer status: ", error);
      throw error;
    }

    // Create invoice
    const invoice = await stripe.invoices.create({
      customer: req.body.stripeId,
      auto_advance: true,
      currency: "usd",
      collection_method: "charge_automatically",
      metadata: {
        offerId: req.body.offerId,
        customer_name: req.body.fullName,
      },
    });

    //create invoice item
    const invoiceItem = await stripe.invoiceItems.create({
      customer: req.body.stripeId,
      amount: Number(req.body.amount) * 100,
      invoice: invoice.id,
      description: "Goodwork Fees for " + req.body.offerId,
    });

    // Finalize
    await stripe.invoices.finalizeInvoice(invoice.id);

    res.status(200).json({ status: true, message: "OK" });
  } catch (e) {
    await queries.setOfferStatus(req.body.offerId, "Hold", "0", "1");
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// get - customer payment methods
router.get("/customer/customer-payment-method", async (req, res) => {
  if (!Object.keys(req.query).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.query.customerId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  try {
    const customer = await stripe.customers.retrieve(req.query.customerId);
    if (customer.invoice_settings.default_payment_method) {
      return res.status(200).send({
        status: true,
        message: customer.invoice_settings.default_payment_method,
      });
    } else {
      return res.status(404).send({
        status: false,
        message: "No default payment method found",
      });
    }
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// get - check onboarding status | consumes stripeId
router.get("/onboarding-status", async (req, res) => {
  if (!Object.keys(req.query).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  }

  let account;

  try {
    account = await stripe.accounts.retrieve(req.query.stripeId);
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }

  if (account.details_submitted) {
    // The user has completed the onboarding process
    return res
      .status(200)
      .json({ status: true, message: "Onboarding completed" });
  } else {
    // The user has not completed the onboarding process
    return res
      .status(400)
      .json({ status: false, message: "Onboarding not completed" });
  }
});

/* test new subscription method */
router.post("/customer/subscription", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  } else if (!req.body.stripeId || !req.body.amount || !req.body.offerId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }
  var date = moment();
  var amount = parseFloat(req.body.amount) * req.body.length;
  try {
    const subscriptionSchedule = await stripe.subscriptionSchedules.create({
      customer: req.body.stripeId,
      start_date: date.weekday(5).unix(),
      end_behavior: "cancel",
      phases: [
        {
          items: [
            {
              price_data: {
                currency: "usd",
                product: "prod_PtG5UvZ5zItdI0",
                recurring: {
                  interval: "week",
                },
                unit_amount_decimal: amount.toFixed(2),
              },
              quantity: 1,
            },
          ],
          iterations: req.body.length,
          collection_method: "charge_automatically",
          metadata: {
            offerId: req.body.offerId,
            customer_name: req.body.fullName,
          },
        },
      ],
    });

    return res.status(200).send({
      status: true,
      message: "Payment schedule started",
    });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

module.exports = router;
