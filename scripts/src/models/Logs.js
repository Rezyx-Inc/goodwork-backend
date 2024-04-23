const mongoose = require('mongoose');

const logsSchema = mongoose.Schema({

    userId: {
        type: String,
        required: true
    },
    integration: {
        type: String,
        required: true
    },
    api: []
});

const integrationsDB = mongoose.connection.useDb(process.env.MONGODB_INTEGRATIONS_DATABASE_NAME);
module.exports = integrationsDB.model('Logs', logsSchema, 'logs');