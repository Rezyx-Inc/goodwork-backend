const express = require("express");
const router = express.Router();
require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET);
var _ = require("lodash");
var { report } = require("../set.js");
const queries = require("../mysql/queries.js");
const moment = require("moment");

router.get("/", (req, res) => {
  res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
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
