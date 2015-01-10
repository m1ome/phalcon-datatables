<html>
  <head>
    <title>Phalcon-DataTables - Phalcon Adapter for jQuery DataTables</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" media="screen">

    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="http://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#example_basic').DataTable({
          serverSide: true,
          ajax: {
            url: "/example_basic",
            method: "POST"
          },
          columns: [
            {data: "id", searchable: false},
            {data: "name"},
            {data: "email"}
          ]
        });
      });
    </script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h1>Phalcon DataTables</h1>
          <h3>Description</h3>
          <p>
            <a href="http://phalconphp.com/">Phalcon Framework</a> adapter for jQuery <a href="http://datatables.net/">DataTables</a>.
          </p>
          <h3>Instructions</h3>
          <p>
            For installation instructions and usage examples, please visit: <a href="https://github.com/m1ome/phalcon-datatables">GitHub</a>
          </p>
          <h3>Example</h3>
          <h4>Basic usage</h4>
          <table id="example_basic">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>