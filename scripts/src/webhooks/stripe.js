require("dotenv").config();

const stripe = require("stripe")(process.env.STRIPE_SECRET);
const express = require('express');
const app = express();

var { report } = require('../set.js')

// This is your Stripe CLI webhook secret for testing your endpoint locally.
const endpointSecret = process.env.STRIPE_WEBHOOK_ENDPOINT_SECRET;

app.post('/webhook', express.raw({type: 'application/json'}), async (request, response) => {

  const sig = request.headers['stripe-signature'];

  let event;

  try {
    event = stripe.webhooks.constructEvent(request.body, sig, endpointSecret);
  } catch (err) {
    response.status(400).send(`Webhook Error: ${err.message}`);
    report("Webhook Error")
    return;
  }

  // Handle the event
  switch (event.type) {
    case 'invoice.paid':
      const invoicePaid = event.data.object;
      report("INVOICE PAID _ Customer : " + invoicePaid.customer_name + " | Offer : " + invoicePaid.metadata.offerId)
      console.log(invoicePaid.metadata)
      break;
    
    case 'invoice.finalized':
      const invoiceFinalized = event.data.object;
      await stripe.invoices.pay(invoiceFinalized.id);
      report("Charging invoice (Attempt) "+invoiceFinalized.id)
      break;
    
    // ... handle other event types
    default:
      console.log(`Unhandled event type ${event.type}`);
  }

  // Return a 200 response to acknowledge receipt of the event
  response.send();
});

app.listen(process.env.STRIPE_WEBHOOK_PORT, () => console.log(`Running on port`,process.env.STRIPE_WEBHOOK_PORT));