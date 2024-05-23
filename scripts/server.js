require("dotenv").config();

const mongoose = require('mongoose');

const express = require('express');
var bodyParser = require('body-parser');
const cors = require('cors'); 
const app = express();

var { report } = require('./src/set.js')

app.use(cors({
    origin: ['http://127.0.0.1:8000', 'http://localhost:8000']
}));

const docsRoute = require('./src/routes/docs');
const integrationsRoute = require('./src/routes/integrations');
const paymentsRoute = require('./src/routes/Payments');

app.use(bodyParser.json({ limit: '130mb' }));
app.use(process.env.FILE_API_BASE_PATH, docsRoute);
app.use(process.env.INTEGRATIONS_API_BASE_PATH, integrationsRoute);
app.use(process.env.PAYMENTS_API_BASE_PATH, paymentsRoute);

// Root Route
app.get('/', (req, res) => {
    res.send('need to do something with this');
});

//Connect to DB
mongoose.connect(process.env.MONGODB_FILES_URI)
.then(() => {
    console.log('Connected to MongoDB');
})
.catch((error) => {
    console.error('Error connecting to MongoDB:', error);
    report("Unable to connect MongoDB in server.js");
});

app.listen(process.env.FILE_API_PORT);

// catches uncaught exceptions
process.on('uncaughtException', async function() {
    console.log("Some issues with the server")
    report("Unexpected Server exit | uncaughtException")
});

/*
some notes:
 - max 25 files per user
 - file size max 5mb
 - max upload at once 130mb
 - integrations server is useful only in dev
*/