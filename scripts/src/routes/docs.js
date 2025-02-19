//Import required libraries and/or modules
const express = require("express"); //To build REST APIs
const router = express.Router(); //To redirect url routes
const Docs = require("../models/Docs");

var fs = require("fs");
var path = require("path");

router.get("/", (req, res) => {
    res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

/*
    /add-docs
    Add documents
    @param workerId required
    @param files required - refer to the Docs model

*/

//POST API to add documents
router.post("/add-docs", async (req, res) => {
    
    //Return false in case of empty request body
    if (!Object.keys(req.body).length) {
    
        return res.status(200).send({ success:false, message:"Empty request" });
    }

    if (!req.body.workerId || !req.body.files) {

         //No file is present in the request (Could be 400)
        if (!Array.isArray(req.body.files) || req.body.files.length == 0) {
            
            return res.status(200).send({ success: false, message: "No file sent." }); 

        } else {

            return res.status(200).send({ success: false, message: "Missing parameters." }); 
        }
    }

    var bsonId;

    // Check if the worker has files, if not, create one
    await Docs.findOne({ workerId: req.body.workerId })
    .then(async (docs) => {

        if (!docs) {

            let doc = await Docs.create(req.body);
            return res.status(200).json({ success: true , message: "Doc created." }); // Return 200, true in case of successful Doc creation
        }

        bsonId = docs._id;
    })
    .catch((e) => {
        console.log("Unexpected error", e); // logging the error message in case of Doc creation failure
        res.status(200).send({ success:false, message: e.message }); // Could be 500
    });

    var files = req.body.files;

    // Get files length
    let documentsLength = await Docs.aggregate([{$match: {workerId: req.body.workerId}}, {$project: {files: {$size: '$files'}}}]);
    
    // check if files length is less than 25, else return error message (could be 400)
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

            //Check if the file size is within the pre-defined limit, if yes insert
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

                // Log and return the error message, if there is an issue in updating the document
                console.log("Unable to Update document.", e); 
                return res.status(200).send({ success:false, message: e.message });
            }
        }
    }
    
    return res.status(200).json({ success: true, message: "File(s) Added." }); // Return the success message
});

/*
    /get-docs
    Get documents by workerId
    @param workerId required
*/

//POST API to get the docs list based on the worker id
router.post("/get-docs", async (req, res) => {

    // Check and return if the request is invalid (Could be 400)
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success:false, message: "Empty request" });

    } else if (!req.body.workerId) {

        return res.status(200).send({ success: false, message: "Missing parameters." });
    }

    let doc = await Docs.findOne({ workerId: req.body.workerId })
        .then((docs) => {

            // Check and return if the doc is not found
            if (!docs) {

                return res.status(200).send({ success: false, message: "Document not found." }); // COuld be 404
            }

            let docsFiles = docs.files;

            // Check if any of the files is saved to disk and get it
            for (let [value, index] in docsFiles) {

                if(value.content == ''){

                    docs.files[index].content = fs.readFileSync(value.path).toString();
                }
            }

            return res.status(200).send({ success:true, message: "Documents found.", data: { docs: docs } }); // Return success message
        })
        .catch((e) => {

            //Log and Return the error message (Could be 500)
            console.log("Unexpected error", e);
            return res.status(200).send({success:false, message : e.message }); 
        });
});

/*
    /get-doc
    Get document by bsonId (you can get it from list-docs)
    @param bsonId required

*/

//Get API to get a document
router.get("/get-doc", async (req, res) => {

    //Check and return if request is empty (Could be 400)
     if (!Object.keys(req.query).length) {

        return res.status(200).send({ success: false , message: "Empty request" });
    }

    try {

        let doc = await Docs.findOne({ "files._id": req.query.bsonId });

        // Return if doc is not found (Could be 404)
        if (!doc) {

            return res.status(200).send({ success: false , message: "Document not found." });
        }

        for (let file of doc.files) {
            
            if (file._id == req.query.bsonId) {
                
                // check if file is saved on disk
                if(file.content == ''){
                    
                    const base64Content = fs.readFileSync(file.path).toString(); // Reading file content

                    //Return success message
                    return res.status(200).json({
                        success:true,
                        message: "Document found.",
                        data: {
                            name: file.name,
                            content: {
                                data: `${base64Content}`,
                            }
                        }
                    });

                }else{
                    
                    const base64Content = file.content.toString("base64");
                    
                    //Return success message
                    return res.status(200).json({
                        success:true,
                        message: "Document found.",
                        data: {
                            name: file.name,
                            content: {
                                data: `${base64Content}`,
                            }
                        }
                    });
                }
            }
        }

        return res.status(200).send({ success:false, message: "Document found but file not found." }); //(Get info)

    } catch (e) {

        //Log and return error message (Could be 500)
        console.log("Unexpected error", e);
        return res.status(200).send({ success:false, message: e.message });
    }
});

/*
    /list-docs
    Get a list of documents by workerId
    @param workerId required

*/

// GET API to get list of documents
router.get("/list-docs", async (req, res) => {

    //Check and return if the request is empty (Could be 400)
    if (!Object.keys(req.query).length) {

        return res.status(200).send({ success:false, message: "Empty request" });
    }

    let doc = await Docs.findOne({ workerId: req.query.workerId })
        .then((docs) => {

            //Check and return if the documents are not found (Could be 404)
            if (!docs) {

                return res.status(200).send({ success:false, message: "Document not found." });
            }

            //List to hold documents
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

            //Return success message
            return res.status(200).json({ success:true, message: "Documents listed.", data : { list: list } });
        })
        .catch((e) => {

            //Log and return error message (Could be 500)
            console.log("Unexpected error", e);
            return res.status(200).send({ success:false, message: e.message });
        });
});

/*
    /del-doc
    delete document by bsonId (you can get it from list-docs)
    @param bsonId required

*/

//POST API
router.post("/del-doc", async (req, res) => {

    if (!Object.keys(req.body).length) {
        return res.status(400).send({ success: false, message: "Empty request" });
    }

    try {
        const docs = await Docs.findOne({ "files._id": req.body.bsonId });

        if (!docs) {
            return res.status(404).send({ success: false, message: "Document not found." });
        }

        // Find the index of the file to delete
        const fileIndex = docs.files.findIndex(file => file._id == req.body.bsonId);

        if (fileIndex === -1) {
            return res.status(404).send({ success: false, message: "File not found in document." });
        }

        // Remove the file
        docs.files.splice(fileIndex, 1);

        await docs.save();

        return res.status(200).send({ success: true, message: "Document deleted." });

    } catch (e) {
        // console.log("Unexpected error", e);
        return res.status(500).send({ success: false, message : e.message });
    }
});

/*
    /del-docs
    delete documents by bsonIds (you can get it from list-docs)
    @param Array(bsonId) required

*/

//POST API to delete a document, based on file id (Could be a DELETE API)
router.post("/del-docs", async (req, res) => {

    //Check and return if the request is empty (Could be 400)
    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    let doc = await Docs.findOne({ "files._id": { $in: req.body.bsonIds } })
        .then((docs) => {

            //Check and return if the document is not found (Could be 404)
            if (!docs) {

                return res.status(200).send({ success: false, message: "Document not found." });
            }

            //(Get info)
            if (docs.length > 1) {

                return res.status(200).send({ success: false, message: "Provide BSON IDs from a single user at the time." });
            }

            let filesFilter = docs.files.filter((file) => {

                return req.body.bsonIds.indexOf(file._id.toString()) == -1;
            });

            docs.files = filesFilter;

            docs.save()
                .then((docs) => {

                    return res.status(200).send({ success: true, message: "Documents deleted." }); // Return success message
                })
                .catch((e) => {

                    //Log and return error message (Could be 500)
                    console.log("Unable to save document.", e);
                    return res.status(200).send({ success: false, message: "Unable to save document." });
                });
        })
        .catch((e) => {

            //Log and return error message (Could be 500)
            console.log("Unexpected error", e);
            return res.status(200).send({ success: false, message: e.message });
        });
});

module.exports = router;
