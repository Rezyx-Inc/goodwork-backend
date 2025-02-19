//Import required libraries and/or modules
const express = require("express"); //To build REST APIs
const router = express.Router(); //To redirect url routes

//Dotenv to read environment variables from the .env file
require("dotenv").config();

const stripe = require("stripe")(process.env.STRIPE_SECRET);
var _ = require("lodash");
var { report } = require("../set.js");
const queries = require("../mysql/queries.js");
const moment = require("moment");

router.get("/", (req, res) => {
  res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

/* Recruiters - Organizations (orgs) */

// POST API to create a customer
router.post("/customer/create", async (req, res) => {

  //Check and return if the request body is empty
  if (!Object.keys(req.body).length) {

    return res.status(400).send({ status: false, message: "Empty request" });

  } else if (!req.body.email) {

    return res
      .status(400)
      .send({ status: false, message: "Missing parameter." });
  }

  // Check and return if the customer already exists
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

    //Log and return error response (Could be 500)
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

    res.status(200).json({ status: true, message: portal }); //Return success response

  } catch (e) {

    //Log and return error response (Could be 500)
    return res.status(400).send({ status: false, message: e.message });
  }
});

//GET API to get customer payment methods
router.get("/customer/customer-payment-method", async (req, res) => {

  //Check and return if the request body is empty or invalid
  if (!Object.keys(req.query).length) {

    return res.status(400).send({ status: false, message: "Empty request" });
    
  } else if (!req.query.customerId) {

    return res
      .status(400)
      .send({ status: false, message: "Missing parameters." });
  }

  try {

    //Fetch customer details using customer ID
    const customer = await stripe.customers.retrieve(req.query.customerId);

    if (customer.invoice_settings.default_payment_method) {

      //Return success message
      return res.status(200).send({
        status: true,
        message: customer.invoice_settings.default_payment_method,
      });

    } else {

      //Return failure message
      return res.status(404).send({
        status: false,
        message: "No default payment method found",
      });
    }

  } catch (e) {

    //Log and return error response (Could be 500)
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

//GET API to get (check) onboarding status | consumes stripeId
router.get("/onboarding-status", async (req, res) => {

  //Check and return if the request body is empty
  if (!Object.keys(req.query).length) {
    return res.status(400).send({ status: false, message: "Empty request" });
  }

  let account;

  try {

    account = await stripe.accounts.retrieve(req.query.stripeId); //Fetch accound ID using stripe ID

  } catch (e) {

    //Log and return error response
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

// POST API to create a subscription/payment
router.post("/customer/subscription", async (req, res) => {

  //Check and return if the request body is empty or invalid
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

      //Return error response
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

    //Log and return error response (Could be 500)
    return res.status(400).send({ status: false, message: e.message });
  }
});

//POST API to List subscriptions (Could be a GET API)
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
      .send({ status: true, message: subscriptionSchedules.data }); //Return success response
  } catch (e) {

    //Log and return error response (Could be 500)
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

//POST API to Retrieve a Subscription
router.post("/customer/subscription/ret", async (req, res) => {

  //Check and return if the request body is empty or invalid
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
      .send({ status: true, message: subscriptionSchedule }); //Return success response
  } catch (e) {

    //Log and return error response
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

//POST API to cancel a Subscription
router.post("/customer/subscription/cancel", async (req, res) => {

  //Check and return if the request body is empty
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
      .send({ status: true, message: subscriptionSchedule.status }); //Return success response
  } catch (e) {

    //Log and return error message (Could be 500)
    console.log(e);
    return res.status(400).send({ status: false, message: e.message });
  }
});

module.exports = router;
