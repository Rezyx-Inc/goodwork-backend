const express = require("express");
const router = express.Router();
const Docs = require("../models/Docs");

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
        return res.status(400).send("Empty request");
    }
    if (!req.body.workerId || !req.body.files) {
        if (!Array.isArray(req.body.files) || req.body.files.length == 0) {
            return res.status(400).send("No file sent.");
        } else {
            return res.status(400).send("Missing parameters.");
        }
    }

    var files = req.body.files;

    let doc = await Docs.findOne({ workerId: req.body.workerId })
        .then(async (docs) => {
            if (!docs) {
                let doc = await Docs.create(req.body);
                return res.status(200).json({ ok: true });
            }

            // check if files length is less than 25
            if (docs.files.length + files.length > 25) {
                return res
                    .status(500)
                    .json({ error: "Max 25 files per user." });
            }

            for (let file of files) {
                docs.files.push(file);
            }

            docs.save()
                .then((docs) => {
                    return res.status(200).json({ ok: true });
                })
                .catch((e) => {
                    console.log("Unable to save document.", e);
                    return res.status(400).send(e.message);
                });
        })
        .catch((e) => {
            console.log("Unexpected error", e);
            res.status(400).send(e);
        });
});

/*
    /get-docs
    Get documents by workerId
    @param workerId required
*/
router.post("/get-docs", async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    } else if (!req.body.workerId) {
        return res.status(400).send("Missing parameters.");
    }

    let doc = await Docs.findOne({ workerId: req.body.workerId })
        .then((docs) => {
            if (!docs) {
                return res.status(404).send("Document not found.");
            }

            return res.status(200).send(docs);
        })
        .catch((e) => {
            console.log("Unexpected error", e);
            return res.status(400).send("Unexpected error.");
        });
});

/*
    /get-doc
    Get document by bsonId (you can get it from list-docs)
    @param bsonId required

*/
router.get("/get-doc", async (req, res) => {
    if (!Object.keys(req.query).length) {
        return res.status(400).send("Empty request");
    }

    try {
        let doc = await Docs.findOne({ "files._id": req.query.bsonId });
        if (!doc) {
            return res.status(404).send("Document not found.");
        }

        for (let file of doc.files) {
            if (file._id == req.query.bsonId) {
                const base64Content = file.content.toString("base64");

                return res.status(200).json({
                    name: file.name,
                    content: {
                        data: `${base64Content}`,
                    },
                });
            }
        }

        return res.status(400).send("Document found but file not found.");
    } catch (e) {
        console.log("Unexpected error", e);
        return res.status(400).send("Unexpected error.");
    }
});

/*
    /list-docs
    Get a list of documents by workerId
    @param workerId required

*/
router.get("/list-docs", async (req, res) => {
    if (!Object.keys(req.query).length) {
        return res.status(400).send("Empty request");
    }

    let doc = await Docs.findOne({ workerId: req.query.workerId })
        .then((docs) => {
            if (!docs) {
                return res.status(404).send("Document not found.");
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

            return res.status(200).json(list);
        })
        .catch((e) => {
            console.log("Unexpected error", e);
            return res.status(400).send("Unexpected error.");
        });
});

/*
    /del-doc
    delete document by bsonId (you can get it from list-docs)
    @param bsonId required

*/
router.post("/del-doc", async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    }

    let doc = await Docs.findOne({ "files._id": req.body.bsonId })
        .then((docs) => {
            if (!docs) {
                return res.status(404).send("Document not found.");
            }

            for (let [index, file] of docs.files.entries()) {
                if (file._id == req.body.bsonId) {
                    // const filesFilter = docs.files.splice(index, 1)

                    // docs.files = filesFilter;

                    // console.log(filesFilter)
                    docs.files.splice(index, 1);

                    docs.save()
                        .then((docs) => {
                            return res.status(200).send("OK");
                        })
                        .catch((e) => {
                            console.log("Unable to save document.", e);
                            return res
                                .status(400)
                                .send("Unable to save document.");
                        });
                }
            }
        })
        .catch((e) => {
            console.log("Unexpected error", e);
            return res.status(400).send("Unexpected error.");
        });
});

/*
    /del-docs
    delete documents by bsonIds (you can get it from list-docs)
    @param Array(bsonId) required

*/
router.post("/del-docs", async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    }

    let doc = await Docs.findOne({ "files._id": { $in: req.body.bsonIds } })
        .then((docs) => {
            if (!docs) {
                return res.status(404).send("Document not found.");
            }

            if (docs.length > 1) {
                return res.status(500).send("Only 1 user at a time");
            }

            let filesFilter = docs.files.filter((file) => {
                return req.body.bsonIds.indexOf(file._id.toString()) == -1;
            });

            docs.files = filesFilter;

            docs.save()
                .then((docs) => {
                    return res.status(200).send("OK");
                })
                .catch((e) => {
                    console.log("Unable to save document.", e);
                    return res.status(400).send("Unable to save document.");
                });
        })
        .catch((e) => {
            console.log("Unexpected error", e);
            return res.status(400).send("Unexpected error.");
        });
});

module.exports = router;
