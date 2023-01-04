// https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/JavaScript/RequireJS/Extensions/Index.html
define(["tablesort"], function () {
  // https://tristen.ca/tablesort/demo/
  let tables = document.querySelectorAll('.tx_httpmonitoring .table')

  for (let i = 0; i < tables.length; i++) {
    new Tablesort(tables[i], {descending: true});
  }
});
