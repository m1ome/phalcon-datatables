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

<h4>Basic usage</h4>
<p>Usage with sorting and searching (It does a "OR" filtering)</p>

<code>Controller</code>
<pre>
$app->post('/example_basic', function() use($app) {
  $builder = new \DataTables\Adapters\QueryBuilder();
  $builder->columns('id, name, email')
          ->from('Example\Models\User');
          
  echo $builder->getResponse()->getContent();
});
</pre>

<code>Javascript</code>
<pre>
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
</pre>
<br>

<table id="example_basic" class="table table-striped">
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