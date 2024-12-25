const express = require("express");
const router = express.Router();
const Docs = require("../models/Docs");

var fs = require("fs");
var path = require("path");

router.get("/", (req, res) => {
    res.send("Docs page");
});

/*
    /add-docs
    Add documents
    @param workerId required
    @param files required - refer to the Docs model

*/
router.post("/add-docs", async (req, res) => {
    
    if (!Object.keys(req.body).length) {

        return res.status(200).send({success:false, message:"Empty request"});
    }

    if (!req.body.workerId || !req.body.files) {

        if (!Array.isArray(req.body.files) || req.body.files.length == 0) {

            return res.status(200).send({ success: false, message: "No file sent."});

        } else {

            return res.status(200).send({success: false, message: "Missing parameters."});
        }
    }

    var bsonId;

    // Check if the worker has files, if not, create one
    await Docs.findOne({ workerId: req.body.workerId })
    .then(async (docs) => {

        if (!docs) {

            let doc = await Docs.create(req.body);
            return res.status(200).json({ success: true , message: "Doc created."});
        }

        bsonId = docs._id;
    })
    .catch((e) => {
        console.log("Unexpected error", e);
        res.status(200).send({success:false. message: e});
    });

    var files = req.body.files;

    // Get files length
    let documentsLength = await Docs.aggregate([{$match: {workerId: req.body.workerId}}, {$project: {files: {$size: '$files'}}}]);
    
    // check if files length is less than 25
    if ((documentsLength[0].files + files.length) > 25) {

        return res
            .status(200)
            .json({ success:false, message: "Max 25 files per user." });
    }

    // Iterate the files array and update the records in the db
    for (let file of files) {

        try{

            file.path = "";
            await Docs.findOneAndUpdate({_id:bsonId}, {'$push': {files:file}});

        }catch(e){

            if(e.code == 10334){
                
                console.log("File too big, saving to disk.");
                file.path = path.join(process.cwd(), process.env.FILES_STORAGE_DIR_NAME, req.body.workerId , (Date.now() + '-' +file.name));
                
                if (!fs.existsSync(path.join(process.cwd(), process.env.FILES_STORAGE_DIR_NAME, req.body.workerId ))) {
                    fs.mkdirSync(path.join(process.cwd(), process.env.FILES_STORAGE_DIR_NAME, req.body.workerId ));
                }

                fs.writeFileSync(file.path, file.content, {flag: 'w+'});

                file.content = "";

                await Docs.findOneAndUpdate({_id:bsonId}, {'$push': {files:file}});

            }else{

                console.log("Unable to Update document.", e);
                return res.status(200).send({success:false, message: e.message});
            }
        }
    }
    
    return res.status(200).json({ success: true, message: "File(s) Added." });
});

/*
    /get-docs
    Get documents by workerId
    @param workerId required
*/
router.post("/get-docs", async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success:false, message: "Empty request"});

    } else if (!req.body.workerId) {

        return res.status(200).send({success: false, message: "Missing parameters."});
    }

    let doc = await Docs.findOne({ workerId: req.body.workerId })
        .then((docs) => {

            if (!docs) {

                return res.status(200).send({success: false, message: "Document not found."});
            }

            let docsFiles = docs.files;

            // Check if any of the files is saved to disk and get it
            for (let [value, index] in docsFiles) {

                if(value.content == ''){

                    docs.files[index].content = fs.readFileSync(value.path).toString();
                }
            }

            return res.status(200).send({success:true, message: docs});
        })
        .catch((e) => {

            console.log("Unexpected error", e);
            return res.status(200).send({success:false. message : "Unexpected error."});
        });
});

/*
    /get-doc
    Get document by bsonId (you can get it from list-docs)
    @param bsonId required

*/
router.get("/get-doc", async (req, res) => {

    if (!Object.keys(req.query).length) {

        return res.status(200).send({success: false , message: "Empty request"});
    }

    try {

        let doc = await Docs.findOne({ "files._id": req.query.bsonId });

        if (!doc) {

            return res.status(200).send({success: false , message: "Document not found."});
        }

        for (let file of doc.files) {
            
            if (file._id == req.query.bsonId) {
                
                // check if file is saved on disk
                if(file.content == ''){
                    
                    const base64Content = fs.readFileSync(file.path).toString();

                    return res.status(200).json({
                        success:true,
                        name: file.name,
                        content: {
                            data: `${base64Content}`,
                        },
                    });

                }else{
                    
                    const base64Content = file.content.toString("base64");

                    return res.status(200).json({
                        success:true,
                        name: file.name,
                        content: {
                            data: `${base64Content}`,
                        },
                    });
                }
            }
        }

        return res.status(200).send({success:false, message: "Document found but file not found."});

    } catch (e) {

        console.log("Unexpected error", e);
        return res.status(200).send({success:false, message: "Unexpected error."});
    }
});

/*
    /list-docs
    Get a list of documents by workerId
    @param workerId required

*/
router.get("/list-docs", async (req, res) => {

    if (!Object.keys(req.query).length) {

        return res.status(200).send({success:false, message: "Empty request"});
    }

    let doc = await Docs.findOne({ workerId: req.query.workerId })
        .then((docs) => {

            if (!docs) {

                return res.status(200).send({success:false, message: "Document not found."});
            }

            var list = [];

            for (let file of docs.files) {

                if (file.type == "references") {

                    list.push({
                        name: file.name,
                        id: file._id,
                        type: file.type,
                        displayName: file.displayName,
                        ReferenceInformation: file.ReferenceInformation,
                    });

                } else {

                    list.push({
                        name: file.name,
                        id: file._id,
                        type: file.type,
                        displayName: file.displayName,
                    });
                }
            }

            return res.status(200).json({success:true, message : list});
        })
        .catch((e) => {

            console.log("Unexpected error", e);
            return res.status(200).send({success:false, message: "Unexpected error."});
        });
});

/*
    /del-doc
    delete document by bsonId (you can get it from list-docs)
    @param bsonId required

*/
router.post("/del-doc", async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success:false, message: "Empty request"});
    }

    let doc = await Docs.findOne({ "files._id": req.body.bsonId })
        .then((docs) => {

            if (!docs) {

                return res.status(200).send({success:false, message: "Document not found."});
            }

            for (let [index, file] of docs.files.entries()) {
                if (file._id == req.body.bsonId) {

                    docs.files.splice(index, 1);

                    docs.save()
                    .then((docs) => {

                        return res.status(200).send({success:true, message: "OK"});
                    })
                    .catch((e) => {

                        console.log("Unable to save document.", e);
                        return res
                            .status(200)
                            .send({success:false, message: "Unable to save document."});
                    });
                }
            }
        })
        .catch((e) => {

            console.log("Unexpected error", e);
            return res.status(200).send({success: true, message : "Unexpected error."});
        });
});

/*
    /del-docs
    delete documents by bsonIds (you can get it from list-docs)
    @param Array(bsonId) required

*/
router.post("/del-docs", async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    let doc = await Docs.findOne({ "files._id": { $in: req.body.bsonIds } })
        .then((docs) => {

            if (!docs) {

                return res.status(200).send({success: false, message: "Document not found."});
            }

            // Need to rework this
            if (docs.length > 1) {

                return res.status(200).send({success: false, message: "Provide BSON IDs from a single user at the time."});
            }

            let filesFilter = docs.files.filter((file) => {

                return req.body.bsonIds.indexOf(file._id.toString()) == -1;
            });

            docs.files = filesFilter;

            docs.save()
                .then((docs) => {

                    return res.status(200).send({success: true, message: "OK"});
                })
                .catch((e) => {

                    console.log("Unable to save document.", e);
                    return res.status(200).send({success: false, message: "Unable to save document."});
                });
        })
        .catch((e) => {

            console.log("Unexpected error", e);
            return res.status(200).send({success: false, message: "Unexpected error."});
        });
});

module.exports = router;
