<?php
// import db settings
require 'include/db_config.php';

// Create connection
$db = new mysqli($host, $username, $password, $dbname, $port)
or die("Connection to db failed: " . $db->connect_error);
?>

  <html>
  <head>

    <!-- title and general meta -->
    <title>Kettering Student Organizations</title>
    <meta charset="utf-8">
    <meta name="description" content="Organizations and Clubs orgranized by Kettering University students.">
    <meta name="keywords" content="organizations,clubs,student,kettering,groups,university">

    <!-- css libraries and styles -->
    <link rel="stylesheet" href="/static/css/bootstrap.css">
    <link rel="stylesheet" href="/static/css/bootstrap-theme.css">
    <link rel="stylesheet" href="/static/css/datatables.min.css">
    <link rel="stylesheet" href="/static/css/highlight/github.css">
    <link rel="stylesheet" href="/static/css/main.css">

    <!-- favicon icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/static/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon/favicon-16x16.png">
    <link rel="manifest" href="/static/favicon/site.webmanifest">
    <link rel="mask-icon" href="/static/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/static/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/static/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!-- CURRENT-PAGE css -->
    <link rel="stylesheet" href="/static/css/clubs.css">

  </head>

  <body>

  <div class="container">

    <!-- header content -->
    <header>
      <div class="top-bar hidden centered">
        <div class="message-bar"></div>
      </div>
    </header>

    <!-- CURRENT-PAGE content -->
    <section>

      <div class="wrapper-vertical">

        <!-- table title -->
        <div class="wrapper-horizontal edge-centered">
          <h4>Kettering Student Organizations</h4>
        </div>

        <!-- table filters -->
        <!-- TODO: integrate these into datatables library as components -->
        <div class="wrapper-horizontal edge-centered">

          <!-- section filter dropdown -->
          <div id="sectionFilter">
            <label>Section:
              <select>
                <option value="A">A</option>
                <option value="B">B</option>
              </select>
            </label>
          </div>

          <!-- day filter radio buttons -->
          <div id="dayFilter">
            <label>Day:
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>M
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>T
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>W
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>Th
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" checked>F
              </label>
            </label>
          </div>

        </div>

      </div>

      <br>
      <div class="clearfix"></div>
      <br>

      <!-- table data -->
      <div class="table-responsive">
        <table id="clubsTable" class="table table-striped table-centered">

          <thead>
          <tr class='element-row'>
            <th data-field="club_id">
              <!-- add table row button -->
              <button id='open-ClubAdd' class='btn btn-success btn-md' data-title="Add" data-toggle="modal"
                      data-target="#add">Add
              </button>
            </th>
            <th data-field="club_name">Club Name</th>
            <th data-field="club_poc">Point of Contact</th>
            <th data-field="club_poc_email">POC Email</th>
            <th data-field="club_meeting_days">Meeting Days</th>
            <th data-field="club_meeting_time">Meeting Time</th>
            <th data-field="club_meeting_loc">Meeting Location</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
          </thead>
          <tbody>
          <?php
          $res = $db->query("SELECT id as club_id, name as club_name, faculty_advisor as club_poc, faculty_email as club_poc_email, norm_meeting_days as club_meeting_days, norm_meeting_time as club_meeting_time, norm_meeting_loc as club_meeting_loc FROM Club");
          if ($res->num_rows == 0) {
            return;
          }
          while ($row = $res->fetch_assoc()) {
            ?>
            <tr class='element-row'>
              <td class='club_id'><?php echo $row['club_id'] ?></td>
              <td class='club_name'><?php echo $row['club_name'] ?></td>
              <td class='club_poc'><?php echo $row['club_poc'] ?></td>
              <td class='club_poc_email'><?php echo $row['club_poc_email'] ?></td>
              <td class='club_meeting_days'><?php echo $row['club_meeting_days'] ?></td>
              <td class='club_meeting_time'><?php echo $row['club_meeting_time'] ?></td>
              <td class='club_meeting_loc'><?php echo $row['club_meeting_loc'] ?></td>
              <td>
                <p data-placement='top' data-toggle='tooltip' title='Edit'>
                  <button id='open-Update' class='open-Update btn btn-primary btn-xs' data-title='Edit'
                          data-toggle='modal' data-target='#edit'>
                    <span class='icon-edit'></span>
                  </button>
                </p>
              </td>
              <td>
                <p data-placement='top' data-toggle='tooltip' title='Delete'>
                  <button id='open-Delete' class='open-Delete btn btn-danger btn-xs' data-title='Delete'
                          data-toggle='modal' data-target='#delete'>
                    <span class='icon-delete'></span>
                  </button>
                </p>
              </td>
            </tr>
            <?php
          }
          $res->free();
          ?>
          </tbody>
        </table>
      </div>
  </div>

  </section>

  <!-- footer content -->
  <!-- not used yet -->
  <footer>
    <div class="bottom-bar hidden centered">
      <div></div>
    </div>
  </footer>

  <!-- edit modal -->
  <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--              {% block edit_modal %}-->
        <!--              {% endblock %}-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- add modal -->
  <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="Add" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--              {% block add_modal %}-->
        <!--              {% endblock %}-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- delete modal -->
  <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--              {% block delete_modal %}-->
        <!--              {% endblock %}-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  </div>

  <script src="/static/js/jquery.js"></script>
  <script src="/static/js/bootstrap.js"></script>
  <script src="/static/js/datatables.min.js"></script>
  <script src="/static/js/highlight/highlight.pack.js"></script>
  <script src="/static/js/main.js"></script>

  <script type="application/javascript">
      window.RAPID = {};
      $(document).ready(function () {
          /* query param actions */
          if (getQueryString('action') === 'add') {
              $('#add').modal('show');
          }
          /* add code syntax highlighting */
          $('pre code').each(function (i, block) {
              hljs.highlightBlock(block);
          });
      });
  </script>

  <!-- CURRENT-PAGE js -->
  <script src="/static/js/clubs.js"></script>

  </body>
  </html>

<?php
// close db connection
$db->close();
?>