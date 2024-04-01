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

module.exports = mongoose.model('Docs', DocsSchema);