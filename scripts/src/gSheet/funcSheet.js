const sha256 = require('sha256');

async function lineUpdated(oldFile, currentFile) {
  const updatedLines = [];
  let  workfile = jsonData

  currentFile.forEach((newRow, index) => {
    const oldRow = oldFile[index];
    if (oldRow && JSON.stringify(newRow) !== JSON.stringify(oldRow)) {
      updatedLines.push({ old: oldRow, new: newRow });
    }
  });
  // for [index, item] of/in Object.entries(currentFile)


  if (updatedLines.length > 0) {
    console.log('lignes modifiées:', updatedLines);
    return true
  } else {
    console.log('nooooo');
    return false
  }
}

async function lineAdded(oldFile, currentFile) {
  const addedLines = [];
  
  const addedLineds = currentFile.slice(oldFile.length);
  if (addedLines.length > 0) {
    console.log('Added Lines:', addedLines);
  } else {
    console.log('nnn');
  }
}



async function lineDeleted(oldFile, currentFile) {
  const deletedLines = oldFile.slice(currentFile.length);
  if (deletedLines.length > 0) {
    console.log('Deleted Lines:', deletedLines);
  } else {
    console.log('nnnnnnnnn');
  }
}

module.exports = { lineUpdated, lineAdded , lineDeleted };
