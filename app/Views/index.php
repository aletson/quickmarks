<!-- CONTENT -->

<section>

  <h1>About this page</h1>

  <?php /* <pre><?php print_r($json_marks); ?></pre> */ ?>
  <?php
  foreach ($expansions as $thisExpansion) {
    echo '<h3 class="display-4"><center>' . $thisExpansion->name . '</center></h3>';
    foreach ($thisExpansion->zones as $thisZone) {
      echo '<h5>' . $thisZone->name . '</h5>';
  ?>
      <table class="table table-sm table-dark table-striped">
        <tbody>
          <?php
          foreach ($thisZone->instances as $thisInstance) {
            if ($thisInstance->marks) {
              foreach ($thisInstance->marks as $thisMark) {
          ?>
                <tr data-mark="<?= $thisMark->id; ?>" data-instance-id="<?= $thisMark->instance; ?>">
                  <td><?php echo $thisMark->name; ?> <?php if (count($thisZone->instances) > 1) echo '<span class="badge badge-pill badge-light">' . $thisMark->instance . '</span>'; ?><?php if (isset($thisMark->nickname)) echo '<small><small><i><span class="text-muted">&nbsp;' . $thisMark->nickname . '</span></i></small></small>'; ?></td>
                  <td>last reported <span class="time" data-killed="<?= isset($thisMark->last_kill) ? $thisMark->last_kill : 'never'; ?>"></span><?php if (!isset($thisMark->last_kill) || $thisMark->last_kill < time() - 14400) { ?>
                      <!-- create button if >4 hours --><button class="btn btn-outline-light markButton btn-sm" data-mark="<?= $thisMark->id; ?>" data-instance="<?= $thisMark->instance; ?>">&nbsp;mark dead&nbsp;</button><?php } ?>
              <?php }
            }
          } ?>
        </tbody>
      </table>
  <?php
    }
  } ?>
</section>

<!-- FOOTER: DEBUG INFO + COPYRIGHTS -->

<footer>

  <div class="copyrights">

    <p>&copy; <?= date('Y') ?> alli.</p>

  </div>

</footer>

<!-- SCRIPTS -->

<script>
  function regenCountdowns() {
    var newDate = new Date;
    newDate = Math.floor(newDate.getTime() / 1000); // seconds
    $('.time').each(function() {
      if ($(this).data('killed') != 'never') {
        var difference = newDate - $(this).data('killed');
        var hoursAgo = Math.floor(difference / 3600); //seconds / 60 = minutes, minutes / 60 = hours
        var color = 'red';
        if (hoursAgo >= 4 && hoursAgo < 6) {
          color = 'darkgoldenrod';
        } else if (hoursAgo >= 6) {
          color = 'green';
        }
        var minutesAgo = Math.floor(difference / 60 % 60);
        if (minutesAgo < 10) {
          minutesAgo = '0' + minutesAgo;
        }
        $(this).text(hoursAgo + ':' + minutesAgo + ' ago');
        $(this).css('color', color);
      }
    });
  };

  $(document).ready(function() {
    $('.time').each(function() {
      if ($(this).data('killed') == 'never') {
        $(this).append('never');
      }
    });
  }).on('click', '.markButton', function() {
    var thisButton = $(this);
    var mark = $(this).data('mark');
    var instance = $(this).data('instance');
    $.post('/killed', {
        "id": mark,
        "instance": instance,
        "time": Date.now() / 1000
      }) // This can be used to do TOD reporting too, just set the post to the filled value rather than Date.now.
      .done(function(data) {
        thisButton.parent().find('.time').each(function() {
          $(this).data('killed', parseInt(Date.now() / 1000));
        });
        thisButton.hide();
      }).fail(function(data) {

      });
  }).on('click', '#scoutTextParser', function() {
    let instanceOne = "";
    let instanceTwo = "";
    let instanceThree = "";
    let mapSymbol = "";
    let lines = $('#scoutText').val().split('\n');
    for (const line of lines) {
      // get zone name
      // get instance if exists
      // get xy
    }
  });
  setInterval(regenCountdowns, 1000);

  // check every second if we're above 14400 seconds after and creat ebuttons if necessary
  // also timers counters or something eventually
</script>


</body>

</html>
