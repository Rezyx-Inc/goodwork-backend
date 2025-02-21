const express = require("express");
const router = express.Router();
const axios = require('axios');

const newApplicationsWebhook = "https://discord.com/api/webhooks/1342489357009031258/izvYM05z4b8L4qRbsRH5_DCof1ksiQot1qp55Y6zyl2OQsSj5t-TeJAs9cgwOiogNMXt";
const newOffersWebhook = "https://discord.com/api/webhooks/1342489629626208307/pGfkW7qa24LdkeRZHftatCMPfrycdecmlraQwnRmk9BYvJHgrcFLFvHXE0mQVa-1u5GD";
const newSignupsWebhook = "https://discord.com/api/webhooks/1342489736832356353/mlS-KZKGJkUT2Qct92PaQHqwa6IyoviF7_uRrsw28bAk49Vg3tDBos169j0hCAN0odA8";

router.get("/", (req, res) => {
    res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

//POST API send a new application webhook
router.post("/newApplication", async (req, res) => {

	try{

		await axios.post(newApplicationsWebhook, {
          content: "Testing new applications discord notification",
        });

		return res.status(200).send({success: true, message: "notification sent"});

	}catch(e){

		return res.status(500).send({success: false, message: "Unable to send New Application Notification"});
	}

});

module.exports = router;
