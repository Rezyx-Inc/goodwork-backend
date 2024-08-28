const mongoose = require('mongoose');

const DocsSchema = mongoose.Schema({

    workerId: {
        type: String,
        required: true
    },
    // Files is a subdocument of documents uploaded by the workers
    files: [
        {
            name: {type: String, required: false },
            uploaded: {type: Date, required: true, default: Date.now },
            modified: {type: Date, required: true, default: Date.now },
            content: {type: Buffer, required: false }, 
            path: {type: String, required: false}, // Path to the file in the file system
            type: {type: String, required: false},  // vaccination, certification, driver's license, diploma, SS Card, reference, skill checklist, other.
            displayName : {type: String, required: false, default: null}, // Display name of the file
            ReferenceInformation: {
                referenceName: { type: String, required: false },
                phoneNumber: { type: String, required: false },
                email: { type: String, required: false },
                dateReferred: { type: Date, required: false },
                minTitle: { type: String, required: false },
                isLastAssignment: { type: Boolean, required: false }
            }
            }
    ]
});

const filesDB = mongoose.connection.useDb(process.env.MONGODB_FILES_DATABASE_NAME);
module.exports = filesDB.model('Docs', DocsSchema);