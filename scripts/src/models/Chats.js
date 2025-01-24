const mongoose = require('mongoose');

const messageSchema = new mongoose.Schema({
    id: String,
    sender: String,
    type: String,
    time: String,
    content: String,
    fileName: String
});

const chatSchema = new mongoose.Schema({
    organizationId: String,
    workerId: String,
    recruiterId: String,
    messages: [messageSchema],
    lastMessage: String,
    isActive: Boolean
}, {
    collection: 'chat'
});


const goodworkDB = mongoose.connection.useDb(process.env.YOUR_DATABASE_NAME);
module.exports = goodworkDB.model('chat', chatSchema);