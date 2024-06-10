const mongoose = require('mongoose');

const DocsSchema = mongoose.Schema({

    workerId: {
        type: String,
        required: true
    },
    // Files is a subdocument of documents uploaded by the workers
    files: [
        {
            name: {type: String, required: true },
            uploaded: {type: Date, required: true, default: Date.now },
            modified: {type: Date, required: true, default: Date.now },
            content: {type: Buffer, required: false },
            path: {type: String, required: false}
        }
    ]
});

const filesDB = mongoose.connection.useDb(process.env.MONGODB_FILES_DATABASE_NAME);
module.exports = filesDB.model('Docs', DocsSchema);