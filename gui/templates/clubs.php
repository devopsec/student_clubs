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
  <div class="wrap">

    <!-- outer content -->
    <div class="content">
      <div class="top-bar hidden centered">
        <div class="message-bar"></div>
      </div>

      <!-- CURRENT-PAGE content -->
      <section class="content-inner">

        <div class="col-md-12">

          <!-- table title and filters -->
          <div class="wrapper-horizontal edge-centered children-align-inherit">
            <h4>Kettering Student Organizations</h4>
            <div class="row">
              <!-- add row button -->
              <button id='open-ClubAdd' class='btn btn-success btn-md' data-title="Add" data-toggle="modal"
                      data-target="#add">Add
              </button>

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

              <!-- search box -->
              <div id="searchFilter">
                <label>Search:
                  <input type="text" value="" placeholder="Search">
                  <span class="icon-search"></span>
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
                <th></th>
                <th data-field="club_name">Club Name</th>
                <th data-field="club_poc">DID (or DID pattern)</th>
                <th data-field="club_poc_email">PBX(s)</th>
                <th data-field="club_meeting_days">Notes</th>
                <th data-field="club_meeting_time">Notes</th>
                <th data-field="club_meeting_loc">Notes</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
              </thead>
              <tbody>
              <?php
              $res = $db->query("SELECT name as club_name, faculty_advisor as club_poc, faculty_email as club_poc_email, norm_meeting_days as club_meeting_days, norm_meeting_time as club_meeting_time FROM Club");
              if ($res->num_rows == 0) {
                return;
              }
                while ($row = $res->fetch_assoc($row)) {
                  ?>
                  <tr class='element-row'>
                    <td class='club_name'><?php echo $row['club_name'] ?></td>
                    <td class='club_poc'><?php echo $row['club_poc'] ?></td>
                    <td class='club_poc_email'><?php echo $row['club_poc_email'] ?></td>
                    <td class='club_meeting_days'><?php echo $row['club_meeting_days'] ?></td>
                    <td class='club_meeting_time'><?php echo $row['club_meeting_time'] ?></td>
                    <td class='club_meeting_loc'><?php echo $row['club_meeting_loc'] ?></td>
                    <td>
                      <p data-placement='top' data-toggle='tooltip' title='Edit'>
                        <button id='open-Update' class='open-Update btn btn-primary btn-xs' data-title='Edit'
                                data-toggle='modal' data-target='#edit'><span class='glyphicon glyphicon-pencil'></span>
                        </button>
                      </p>
                    </td>
                    <td>
                      <p data-placement='top' data-toggle='tooltip' title='Delete'>
                        <button id='open-Delete' class='open-Delete btn btn-danger btn-xs' data-title='Delete'
                                data-toggle='modal' data-target='#delete'><span
                              class='glyphicon glyphicon-trash'></span>
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

      </section>
    </div>

  </div>
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

