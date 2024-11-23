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

/* Workers

@note : Commented for now since we won't use these methods ------------------------------------

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

*/
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

  const portal = process.env.STRIPE_CUSTOMER_PORTAL;

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
/*
@note : Disabled in favor of scheduled payments ---------------------------------------

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
*/

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

// Create a subscription/payment
router.post("/customer/subscription", async (req, res) => {

  if (!Object.keys(req.body).length) {

    return res.status(400).send({ status: false, message: "Empty request" });

  } else if (!req.body.stripeId || !req.body.amount || !req.body.offerId) {

    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  // In 2 weeks from now
  var date = moment().add(2, 'weeks');
  var amount = Number(req.body.amount) / Number(req.body.length);

  try {
    if (amount % 1 != 0) {
      var subscriptionScheduleOptions = {
        customer: req.body.stripeId,
        // Take the first friday
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
                  unit_amount: Math.floor(amount, 2),
                },
                quantity: 1,
              },
            ],
            iterations: Number(req.body.length) - 1,
            collection_method: "charge_automatically",
            metadata: {
              offerId: req.body.offerId,
              customer_name: req.body.fullName,
            },
          },
          {
            items: [
              {
                price_data: {
                  currency: "usd",
                  product: "prod_PtG5UvZ5zItdI0",
                  recurring: {
                    interval: "week",
                  },
                  unit_amount:
                    Number(req.body.amount) -
                    Math.floor(amount, 2) * Number(req.body.length) +
                    Math.floor(amount, 2),
                },
                quantity: 1,
              },
            ],
            iterations: 1,
            collection_method: "charge_automatically",
            metadata: {
              offerId: req.body.offerId,
              customer_name: req.body.fullName,
            },
          },
        ],
      };

    } else if (amount % 1 == 0) {
      var subscriptionScheduleOptions = {
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
                  unit_amount: Math.floor(amount, 2),
                },
                quantity: 1,
              },
            ],
            iterations: Number(req.body.length),
            collection_method: "charge_automatically",
            metadata: {
              offerId: req.body.offerId,
              customer_name: req.body.fullName,
            },
          },
        ],
      };
    } else {
      return res.status(400).send({
        status: false,
        message: "Unable to figuyre a subscription schedule",
      });
    }

    const subscriptionSchedule = await stripe.subscriptionSchedules.create(
      subscriptionScheduleOptions
    );

    return res.status(200).send({
      status: true,
      message: subscriptionSchedule.id,
    });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// List subscriptions
router.post("/customer/subscription/list", async (req, res) => {
  var listOptions = {};
  if (req.body.stripeId && req.body.stripeId.length > 3) {
    listOptions.customer = req.body.stripeId;
  }
  if (req.body.limit && req.body.stripeId.limit >= 0) {
    listOptions.limit = req.body.limit;
  }

  try {
    const subscriptionSchedules = await stripe.subscriptionSchedules.list(
      listOptions
    );
    return res
      .status(200)
      .send({ status: true, message: subscriptionSchedules.data });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// Retrieve a Subscription
router.post("/customer/subscription/ret", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty body" });
  } else if (!req.body.subscriptionScheduleId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing subscription schedule ID." });
  }

  try {
    const subscriptionSchedule = await stripe.subscriptionSchedules.retrieve(
      req.body.subscriptionScheduleId
    );
    return res
      .status(200)
      .send({ status: true, message: subscriptionSchedule });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

// Cancel a Subscription
router.post("/customer/subscription/cancel", async (req, res) => {
  if (!Object.keys(req.body).length) {
    return res.status(400).send({ status: false, message: "Empty body" });
  } else if (!req.body.subscriptionScheduleId) {
    return res
      .status(400)
      .send({ status: false, message: "Missing subscription schedule ID." });
  }

  try {
    const subscriptionSchedule = await stripe.subscriptionSchedules.cancel(
      req.body.subscriptionScheduleId,
      {
        prorate: false,
        invoice_now: false,
      }
    );
    return res
      .status(200)
      .send({ status: true, message: subscriptionSchedule.status });
  } catch (e) {
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

module.exports = router;
