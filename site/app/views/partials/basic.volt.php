<script>
  $(document).ready(function() {
    $('#example_basic').DataTable({
      serverSide: true,
      ajax: {
        url: "/example_querybuilder",
        method: "POST"
      },
      columns: [
        {data: "id", searchable: false},
        {data: "name"},
        {data: "email"},
        {data: "balance", searchable: false}
      ]
    });
  });
</script>

<h4>Basic usage</h4>
<p>Usage with sorting and searching (It does a "OR" filtering)</p>

<code>Controller</code>
<pre>
$app->post('/example_querybuilder', function() use($app) {

  $builder = $app->getService('modelsManager')
                 ->createBuilder()
                 ->columns('id, name, email')
                 ->from('Example\Models\User');

  $dataTables = new \DataTables\DataTable();
  $dataTables->fromBuilder($builder)->sendResponse();

});
</pre>

<code>Javascript</code>
<pre>
$('#example_basic').DataTable({
  serverSide: true,
  ajax: {
    url: "/example_querybuilder",
    method: "POST"
  },
  columns: [
    {data: "id", searchable: false},
    {data: "name"},
    {data: "email"},
    {data: "balance", searchable: false}
  ]
});
</pre>
<br>

<table id="example_basic" class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Balance</th>  
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
