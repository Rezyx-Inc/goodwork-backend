const express = require('express');
const router = express.Router();
require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET);

router.get('/', (req, res) => {
    res.send('Payments page');
});

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
		  type: 'express',
		  country: 'US',
		  email: req.body.email
		});
		
	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}

    // insert into users stripeId values account.id

    res.status(200).json({status: true, message:account.id});
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
		  type: 'account_onboarding',
		});

	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}
    
    // the expiracy is 5 minutes
    return res.status(200).json({status:true, message:accountLink.url})

});

// get - login links | consumes stripeId
router.get('/login-link', async (req, res) => {
    
    if(!Object.keys(req.query).length) {
        return res.status(400).send({status:false, message:"Empty request"})
    }

    try{
    
    	const loginLink = await stripe.accounts.createLoginLink.create(req.query.stripeId);
    
    }catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}
    
    return res.status(200).json({status:true, message:loginLink.url})

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
		  amount:req.body.amount * 100,
		  currency: 'usd',
		  destination: req.body.stripeId
		});
	
	}catch(e){
		console.log(e);
		return res.status(400).send({status: false, message: e.message})
	}

    res.status(200).json({status: true, message:""});
})