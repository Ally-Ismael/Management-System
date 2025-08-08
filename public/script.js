$(document).ready(function() {
    // Function to load transactions from API
    function loadTransactions() {
        $.ajax({
            type: 'GET',
            url: 'car.php',
            success: function(transactions) {
                displayTransactions(transactions);
            },
            error: function(xhr, status, error) {
                alert('Error fetching transactions: ' + error);
            },
            dataType: 'json'
        });
    }

    // Function to display transactions in the table
    function displayTransactions(transactions) {
        var tableBody = $('#transactionTableBody');
        tableBody.empty(); // Clear existing rows

        // Loop through each transaction and append to table
        $.each(transactions, function(index, transaction) {
            var row = $('<tr>');
            row.append('<td>' + transaction.transaction_id + '</td>');
            row.append('<td>' + transaction.license_plate + '</td>');
            row.append('<td>' + transaction.scan_type + '</td>');
            row.append('<td>' + transaction.scanned_at + '</td>');
            row.append('<td>' + (transaction.location ? transaction.location : '') + '</td>');
            tableBody.append(row);
        });
    }

    // Handle form submission for adding a new car
    $('#addCarForm').submit(function(event) {
        event.preventDefault();

        var formData = {
            license_plate: $('#licensePlate').val(),
            make: $('#make').val(),
            model: $('#model').val(),
            color: $('#color').val(),
            year: $('#year').val()
        };

        $.ajax({
            type: 'POST',
            url: 'car.php',
            data: JSON.stringify(formData),
            success: function(response) {
                alert(response.message);
                loadTransactions(); // Refresh transactions after adding car
                $('#addCarForm')[0].reset(); // Reset form
            },
            error: function(xhr, status, error) {
                alert('Error adding car: ' + error);
            },
            dataType: 'json',
            contentType: 'application/json'
        });
    });

    // Initial load of transactions when the page loads
    loadTransactions();
});
