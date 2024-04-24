const express = require('express');
const router = express.Router();
require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET);

router.get('/', (req, res) => {
    res.send('Payments page');
});

/* Workers */

// create a connected account | returns account id
router.post('/create', async (req, res) => {
    
    if(!Object.keys(req.body).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }
    else if ( !req.body.userId || !req.body.email){

        return res.status(400).send({status:false, message:"Missing parameters."})
    }

    try{
	
	    // Create the stripe connected account
	    const account = await stripe.accounts.create({
		  type: 'standard',
		  country: 'US',
		  email: req.body.email
		});
		
		 // insert into users stripeId values account.id

    	res.status(200).json({status: true, message:account.id});

	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}

   
})

// get - account links | consumes stripeId
router.get('/account-link', async (req, res) => {
    
    if(!Object.keys(req.query).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }

    try{
	
	    const accountLink = await stripe.accountLinks.create({
			account: req.query.stripeId,
			refresh_url: process.env.REFRESH_URL_BASE_PATH + "/" + req.query.stripeId,
			return_url: process.env.RETURN_URL_BASE_PATH + "/" + req.query.stripeId,
			type: 'account_onboarding'
		});

		// the expiracy is 5 minutes
    	return res.status(200).json({status:true, message:accountLink.url})

	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}
    
    

});

// get - login links | consumes stripeId
router.get('/login-link', async (req, res) => {
    
    if(!Object.keys(req.query).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }

    try{
    
    	const loginLink = await stripe.accounts.createLoginLink(req.query.stripeId);
    	
    	return res.status(200).json({status:true, message:loginLink.url})
    
    }catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}
    
    

});

// post - transfer
// creates a transfer | returns true or false
router.post('/transfer', async (req, res) => {
    
    if(!Object.keys(req.body).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }
    else if ( !req.body.accountId || !req.body.amount){

        return res.status(400).send({status:false, message:"Missing parameters."})
    }

    try{
	
	    // Create the transfer
	    const account = await stripe.transfers.create({
		  amount: Number(req.body.amount) * 100,
		  currency: 'usd',
		  destination: req.body.stripeId
		});
	
		res.status(200).json({status: true, message:"OK"});

	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}

    
})


/* Recruiters - Employers (orgs) */

// Create a customer
router.post('/customer/create', async (req, res) => {

	if(!Object.keys(req.body).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }
    else if ( !req.body.email){

        return res.status(400).send({status:false, message:"Missing parameter."})
    }

    let portal = "https://billing.stripe.com/p/login/test_8wMaFa19ddXy1wc5kk";

    try{

		const customer = await stripe.customers.create({
			email: req.body.email,
			payment_method: 'pm_card_visa',
  			invoice_settings: {
    			default_payment_method: 'pm_card_visa',
  			}
		});

		// insert into users stripeId values customer.id
		res.status(200).json({status: true, message:portal});

	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}

});

// Create an invoice
router.post('/customer/create', async (req, res) => {

	if(!Object.keys(req.body).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }
    else if ( !req.body.stripeId || !req.body.amount){

        return res.status(400).send({status:false, message:"Missing parameters."})
    }

    try{

    	// Create invoice
		const invoice = await stripe.invoices.create({
			customer: req.body.stripeId,
			auto_advance: false,
			currency: "usd"
		});

		//create invoice item
		const invoiceItem = await stripe.invoiceItems.create({
			customer: req.body.stripeId,
			amount: Number(req.body.amount) * 100,
			invoice: invoice.id
		});

		// Finalize
		await stripe.invoices.finalizeInvoice(invoice.id);

		// insert into users stripeId values customer.id
		res.status(200).json({status: true, message:portal});

	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}

});

module.exports = router;