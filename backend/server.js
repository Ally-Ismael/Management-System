const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');
const app = express();
const PORT = 3001;

// Serve static files from root directory to access SAP forms.html and assets
app.use(express.static(path.join(__dirname, '..')));

app.use(bodyParser.json());

// Helper function to save form data to network folder
function saveFormToNetworkFolder(formType, formData) {
  let folderPath = '';
  if (formType === 'sapAuth') {
    folderPath = '\\\\datahq01\\Information Systems\\B S A\\Forms\\Authorisation';
  } else if (formType === 'itUser') {
    folderPath = '\\\\datahq01\\Information Systems\\B S A\\Forms\\Network';
  } else if (formType === 'sapNew') {
    folderPath = '\\\\datahq01\\Information Systems\\B S A\\Forms\\Application';
  } else {
    throw new Error('Unknown form type for saving');
  }

  // Ensure folder exists
  if (!fs.existsSync(folderPath)) {
    fs.mkdirSync(folderPath, { recursive: true });
  }

  // Create filename with timestamp
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
  const fileName = `${formType}_form_${timestamp}.json`;
  const filePath = path.join(folderPath, fileName);

  // Write form data as JSON
  fs.writeFileSync(filePath, JSON.stringify(formData, null, 2), 'utf8');

  return filePath;
}

// Submit form endpoint
app.post('/submit-form', (req, res) => {
  const form = req.body;
  if (!form || !form.userEmail || !form.supervisorEmail || !form.formType) {
    return res.status(400).json({ error: 'Missing required fields' });
  }
  try {
    // Save form data to network folder based on formType
    const savedPath = saveFormToNetworkFolder(form.formType, form);

    // Notify supervisor (placeholder, implement as needed)
    console.log(`Notify supervisor at ${form.supervisorEmail} about new form submission.`);

    res.json({ message: 'Form submitted and saved', savedPath });
  } catch (err) {
    console.error('Error submitting form:', err);
    res.status(500).json({ error: 'Failed to submit form' });
  }
});

// Supervisor approves form
app.post('/supervisor-approve', (req, res) => {
  const { formId, supervisorEmail } = req.body;
  if (!formId || !supervisorEmail) {
    return res.status(400).json({ error: 'Missing formId or supervisorEmail' });
  }
  try {
    // Update form status (placeholder, implement as needed)
    console.log(`Supervisor ${supervisorEmail} approved form ${formId}.`);

    // Notify Katrina and Tonata (placeholder)
    const katrinaEmail = 'kagadhinwak@namwater.com.na';
    const tonataEmail = 'UwangaT@namwater.com.na';

    console.log(`Notify ${katrinaEmail} and ${tonataEmail} about form ${formId} awaiting approval.`);

    res.json({ message: 'Supervisor approved, final approvers notified' });
  } catch (err) {
    console.error('Error in supervisor approval:', err);
    res.status(500).json({ error: 'Failed to process supervisor approval' });
  }
});

// Final approval by Katrina or Tonata
app.post('/final-approve', (req, res) => {
  const { formId, approverEmail, approve, formType, formData } = req.body;
  if (!formId || !approverEmail || typeof approve !== 'boolean' || !formType || !formData) {
    return res.status(400).json({ error: 'Missing required fields' });
  }
  try {
    if (approve) {
      // Save approved form data to network folder
      const savedPath = saveFormToNetworkFolder(formType, formData);

      // Notify other approver and user (placeholders)
      const otherApproverEmail =
        approverEmail.toLowerCase() === 'kagadhinwak@namwater.com.na'
          ? 'UwangaT@namwater.com.na'
          : 'kagadhinwak@namwater.com.na';

      console.log(`Notify ${otherApproverEmail} that form ${formId} has been approved by ${approverEmail}.`);
      console.log(`Notify user that form ${formId} has been approved and saved at ${savedPath}.`);

      res.json({ message: 'Form approved and saved', savedPath });
    } else {
      // Handle rejection (placeholder)
      console.log(`Form ${formId} rejected by ${approverEmail}.`);
      res.json({ message: 'Form rejected' });
    }
  } catch (err) {
    console.error('Error in final approval:', err);
    res.status(500).json({ error: 'Failed to process final approval' });
  }
});

// Get form status (placeholder)
app.get('/form-status/:formId', (req, res) => {
  const formId = req.params.formId;
  try {
    // Return dummy status
    res.json({ status: 'Pending Approval' });
  } catch (err) {
    console.error('Error fetching form status:', err);
    res.status(500).json({ error: 'Failed to fetch form status' });
  }
});

app.listen(PORT, () => {
  console.log(`NAMWATER SAP Authorization backend running on port ${PORT}`);
});
