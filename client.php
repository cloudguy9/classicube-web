<?php session_start();
$ip = "ccube.gunawan092w1.eu.org/endpoint";
if (!isset($_SESSION['username'])) { header('Location: /'); exit(); }
$username = $_SESSION['username']; 
if (isset($_GET['logout'])) {unset($_SESSION['username']); header("Location: /"); exit();}?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>AxolotlSMP Classic</title>
    <meta name="viewport" content="width=device-width">
    <meta name="theme-color" content="#9873ac">
    <meta name="canonical-url" content="https://ccube.gunawan092w1.eu.org">
    <meta name="description" content="AxolotlSMP Classic">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
    <link href="https://classicube.net/scss/v2/style/scss/style.scss?v=30" rel="stylesheet" type="text/css">
    <style>
      @import url("https://classicube.net/scss/v2/style/scss/dark.scss?v=30") (prefers-color-scheme: dark);
    </style>
    <meta name="referrer" content="no-referrer-when-downgrade" />
  </head>
  <body>
    <div id="header">
      <div class="row"><a href="/">
          <h1 class="small-12 medium-1 columns">AxolotlSMP</h1>
        </a><span id="mnav_cont" class="show-for-small-only"><label for="navtoggle">
            <div id="navtoggle_btn"><i class="fi-list"></i></div>
          </label><input type="checkbox" style="display:none;" id="navtoggle">
          <div id="mnav">
            <p>Welcome, <?php echo htmlspecialchars($username); ?>!<a href="?logout">Not you?</a></p>
          </div></input>
        </span>
        <div id="nav" class="show-for-medium-up">
          <p>Welcome, <?php echo htmlspecialchars($username); ?>!<a href="?logout">Not you?</a></p>
        </div>
      </div>
    </div>
    <div id="body">
      <style>
        #logmsg {
          font-size: 18px;
          font-family: 'Source Sans Pro', sans-serif;
          text-shadow: 1px 1px 5px rgba(0, 0, 0, .5);
          font-weight: bold;
          text-align: center;
          white-space: pre-wrap;
        }

        /* the canvas *must not* have any border or padding, or mouse coords will be wrong */
        #canvas {
          display: block;
          box-sizing: border-box;
          border-width: 0px !important;
          padding: 0 !important;
          margin: 0 auto;
          box-shadow: 0 3px 5px rgba(0, 0, 0, .4);
          width: 100%;
          height: auto;
        }
      </style>
      <div class="sec">
        <div class="row"><canvas class="emscripten" id="canvas" style="background-color: black;" oncontextmenu="event.preventDefault()" tabindex=-1 width="1000" height="562"></canvas><span id="logmsg" style="color:#F67;"></span></div>
        <script type='text/javascript' referrerpolicy="no-referrer-when-downgrade">
          function logText(text) {
            console.log(text);
            var logElement = document.getElementById('logmsg');
            logElement.innerHTML = text;
          }
          // ensure game still runs even without IndexedDB
          var idb = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
          if (!idb) {
            alert('IndexedDB unsupported, therefore\nmaps and settings will not save');
            window.mozIndexedDB = {};
          }
          // need to load IndexedDB before running the game
          function preloadIndexedDB() {
            _interop_LoadIndexedDB();
          }

          function forceTouchLayout() {
            var elem;
            try {
              elem = document.getElementById('footer');
              elem.parentNode.removeChild(elem);
              elem = document.getElementById('content');
              elem.parentNode.removeChild(elem);
            } catch (err) {}
          }

          function getCssInt(styles, prop) {
            return parseInt(styles.getPropertyValue(prop), 10);
          }

          function calcViewportWidth(elem) {
            var parent = elem.parentNode;
            var styles = window.getComputedStyle(parent, null);
            return parent.offsetWidth - getCssInt(styles, 'padding-left') - getCssInt(styles, 'padding-right');
          }

          function resizeGameCanvas() {
            var cc_canv = document.getElementById('canvas');
            var dpi = window.devicePixelRatio;
            var aspect_ratio = 16 / 9;
            var viewport_w = calcViewportWidth(cc_canv);
            var viewport_h = viewport_w / aspect_ratio;
            var canv_w = Math.round(viewport_w);
            var canv_h = Math.round(viewport_h);
            if (canv_h % 2) {
              canv_h = canv_h - 1;
            }
            if (canv_w % 2) {
              canv_w = canv_w - 1;
            }
            cc_canv.width = canv_w * dpi;
            cc_canv.height = canv_h * dpi;
          }

          function logFatal(event) {
            Module.setStatus('ClassiCube has crashed (' + event + ')\nPlease report this on the ClassiCube forums or to UnknownShadow200\n\nTo see more details, open Developer Tools and go to Console tab');
            Module.setStatus = function(text) {
              if (event) Module.printErr('[post-exception status] ' + event);
            };
          }
          var Module = {
              preRun: [preloadIndexedDB, resizeGameCanvas],
              postRun: [],
              arguments: ['<?php echo htmlspecialchars($username); ?>', '0', '<?php echo $ip ?>', '443'],
                print : function(text) {
                  if (arguments.length > 1) text = Array.prototype.slice.call(arguments).join(' ');
                  console.log(text);
                },
                printErr : function(text) {
                  if (arguments.length > 1) text = Array.prototype.slice.call(arguments).join(' ');
                  console.error(text);
                },
                canvas: (function() {
                  return document.getElementById('canvas');
                })(),
                setStatus: logText,
                totalDependencies: 0,
                monitorRunDependencies: function(left) {
                  this.totalDependencies = Math.max(this.totalDependencies, left);
                  Module.setStatus(left ? 'Preparing... (' + (this.totalDependencies - left) + '/' + this.totalDependencies + ')' : 'All downloads complete.');
                },
                onAbort: function(why) {
                  logFatal('abort: ' + why);
                }
              };
              Module.setStatus('Downloading...');
              window.onerror = function(event) {
                logFatal(event);
              };

              function onDownloadFailed(src) {
                // retry without CORS
                logText('Failed to download ClassiCube.js, retrying..');
                var root = src.parentNode;
                root.removeChild(src);
                var elem = document.createElement('script');
                elem.setAttribute('async', '');
                elem.setAttribute('src', '/classicube.js');
                root.appendChild(elem);
              }
        </script>
      </div>
      <script async crossorigin type="text/javascript" src="//cdn.classicube.net/client/latest/ClassiCube.js?v=20" onerror="onDownloadFailed(this)" referrerpolicy="no-referrer-when-downgrade"></script>
      <div id="content">
        <div class="container" style="text-align:center;">
          <h3>Default Controls</h3><img src="https://classicube.net/static/img/controls.png" style="margin:auto;">
        </div>
      </div>
    </div>
    <div id="footer" class="row">
      <div class="small-6 medium-4 columns flinks"><a href="/discord/" class="h-discord"><i class="fi-comments"></i> Discord</a></div>
      <div class="small-6 medium-4 columns flinks"><a href="https://classicube.net/privacy/" class="h-general"><i class="fi-eye"></i> Privacy Policy</a></div>
      <div class="small-12 medium-4 columns">
        <p id="copyright">AxolotlSMP Classic</p>
        <p id="copyright">Using ClassiCube</p><a href="/discord/" class="button expand discord" title="Join our Discord"><img src="https://classicube.net/static/v2/img/discord.svg"></a>
      </div>
    </div>
  </body>
</html>