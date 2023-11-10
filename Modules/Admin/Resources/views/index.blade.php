
<script>
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }
    var id = $.urlParam('id');
    console.log(id);
        $(function () {
            $('#challenge-management').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[5,"desc"]],
                bSortable: true,
                bRetrieve: true,
                "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ 0,1,2,3,4,6] },
                { "bSearchable": false, "aTargets": [ 0,6] }
                ],
                ajax: {
                    "url":'{!! Route("admin-participant-list") !!}',
                    "type":"GET",
                    "data":{
                        "cid":id
                    }
                },
                // order: [[6, "desc"]],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'title', name: 'title'},
                    // {data: 'image_name', name: 'image_name'},
                    {data: 'image_name', name: 'image_name', render: function (data, type, row) {
                            return '<img src="'+data+'" height="50px" width="50px" >'
                        }
                    },
                    // {data: 'status', name: 'status', render: function (data, type, row) {
                    //         if (data == '0') {
                    //             return '<span class="label label-sm label-warning">Inactive</span>';
                    //         } else if (data == '1') {
                    //             return '<span class="label label-sm label-success">Active</span>';
                    //         } else if (data == '3') {
                    //             return '<span class="label label-sm label-danger">Delete</span>';
                    //         } else {
                    //             return '';
                    //         }
                    //     }
                    // },
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
        function deleteChallenge(obj) {
            $.confirm({
                title: 'Delete Poll',
                content: 'Are you sure to delete this Poll?',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                        btnClass: 'btn-red',
                        action: function () {
                            window.location.href = $(obj).attr('data-href');
                        }
                    },
                    cancel: function () {}
                }
            });
        }
    </script>
