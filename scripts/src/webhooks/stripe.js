//Import required libraries and/or modules

//To read environment variables from the .env file
require("dotenv").config();

const stripe = require("stripe")(process.env.STRIPE_SECRET);
const express = require("express"); //To build REST APIs
const app = express();

var { report } = require("../set.js");
const queries = require("../mysql/queries.js");

// This is your Stripe CLI webhook secret for testing your endpoint locally.
const endpointSecret = process.env.STRIPE_WEBHOOK_ENDPOINT_SECRET;

app.post(
    "/webhook",
    express.raw({ type: "application/json" }),
    async (request, response) => {
        const sig = request.headers["stripe-signature"]; //Setting request headers

        let event;

        try {
            event = stripe.webhooks.constructEvent(
                request.body,
                sig,
                endpointSecret
            );
        } catch (err) {
            //Return error message, while testing
            response.status(400).send(`Webhook Error: ${err.message}`);
            await report('error', 'stripe.js', "Webhook Error");
            return;
        }

        // send worker payment
        async function sendTransfer(offerId) {
            let workerId;
            try {
                let offer = queries.getOfferDetails(offerId);

                //Check and return if payment is already done
                if (
                    offer.worker_payment_status !== null ||
                    offer.worker_payment_status == "Done"
                ) {
                    console.log("Payment already sent", offerId);
                    return { status: false, msg: "already paid" };
                }

                workerId = offer.workerId;
                let stripeId = queries.getStripeId(workerId);
                let workerTier = queries.getAccountTier(workerId);

                let workerMultiplier = 0;
                switch (workerTier) {
                    case "0":
                    case "1":
                        workerMultiplier = 2;
                        break;
                    case "2":
                        workerMultiplier = 5;
                        break;
                    default:
                        workerMultiplier = 0;
                }

                //Calculate total amount based on no. of workers
                let amount =
                    Number(offer.total_organization_amount) * workerMultiplier;

                // Create the transfer
                const account = await stripe.transfers.create({
                    amount: Number(amount) * 100,
                    currency: "usd",
                    destination: stripeId,
                });

                // DB save worker payment
                //  worker_payment_status "Done" "Pending" null
                await queries.setWorkerPaymentStatus(offerId);
            } catch (e) {

                //Log and return error response
                console.log(e);
                await report('error', 'stripe.js', "Worker Transfer Failed " + workerId);
                return false;
            }
        }

        // Handle the event
        switch (event.type) {
            //Payment done
            case "invoice.paid":
                const invoicePaid = event.data.object;
                //report("INVOICE PAID _ Customer : " + invoicePaid.metadata.customer_name + " | Offer : " + invoicePaid.metadata.offerId)
                // Set the invoice to onboarding
                await queries.setOfferStatus(
                    invoicePaid.metadata.offerId,
                    "Onboarding"
                );
                let workerPayment = await sendTransfer(
                    invoicePaid.metadata.offerId
                );
                //workerPayment ? report("Worker Paid " +invoicePaid.metadata.offerId) : ""
                break;
            
            //Payment finalized (Not paid)
            case "invoice.finalized":
                const invoiceFinalized = event.data.object;
                await stripe.invoices.pay(invoiceFinalized.id);
                //report("Charging invoice (Attempt) "+invoiceFinalized.id)
                break;

            // ... handle other event types
            default:
            //console.log(`Unhandled event type ${event.type}`);
        }

        // Return a 200 response to acknowledge receipt of the event
        response.send();
    }
);

//Assign to a port
app.listen(process.env.STRIPE_WEBHOOK_PORT, () =>
    console.log(`Running on port`, process.env.STRIPE_WEBHOOK_PORT)
);
