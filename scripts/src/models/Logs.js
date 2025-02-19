const mongoose = require('mongoose'); //Required for MongoDB connection

//Create a schema for logs
const logsSchema = mongoose.Schema({

    userId: {   //(Could be unique and number)
        type: String, 
        required: true
    },
    integration: {
        type: String,
        required: true
    },
    api: []
});

//Connect to integrations db
const integrationsDB = mongoose.connection.useDb(process.env.MONGODB_INTEGRATIONS_DATABASE_NAME);
module.exports = integrationsDB.model('Logs', logsSchema, 'logs');