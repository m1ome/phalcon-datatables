<html>
  <head>
    <title>Phalcon-DataTables - Phalcon Adapter for jQuery DataTables</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css" media="screen">


    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="http://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <h1>Phalcon DataTables</h1>
          <h3>Description</h3>
          <p>
            <a href="http://phalconphp.com/">Phalcon Framework</a> adapter for jQuery <a href="http://datatables.net/">DataTables</a>.
          </p>
          <h3>Instructions</h3>
          <p>
            For installation instructions and usage examples, please visit: <a href="https://github.com/m1ome/phalcon-datatables">GitHub</a>
          </p>
          <h3>QueryBuilder Examples</h3>
          <p>
            Usage examples: Basic, Search-by-column, Multiple ordering, Regex search
          </p>
          <br>

          {{ partial('partials/basic') }}
          {{ partial('partials/column_search') }}
          
          <h4>Multiple ordering</h4>
          <h4>Regex search</h4>
        </div>
      </div>
    </div>
  </body>
</html>