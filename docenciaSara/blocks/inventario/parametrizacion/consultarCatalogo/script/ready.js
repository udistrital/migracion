/* Build the DataTable with third column using our custom sort functions */
    $('#example').dataTable( {
        "aaSorting": [ [0,'asc'], [1,'asc'] ],
        "aoColumns": [
            { "sType": 'string-case' },
            
        ]
    } );