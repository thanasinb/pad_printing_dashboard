<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--    <link rel="stylesheet" type="text/css" href="../css/reset.css" />-->
<!--    <link rel="stylesheet" type="text/css" href="../css/akottr.css" />-->
    <link rel="stylesheet" type="text/css" href="css/reorder-columns/dragtable.css" />

    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="js/reorder-columns/jquery.dragtable.js"></script>

    <!-- only for jquery.chili-2.2.js -->
<!--    <script src="//code.jquery.com/jquery-migrate-1.1.1.js"></script>-->
<!--    <script type="text/javascript" src="//akottr.github.io/js/jquery.chili-2.2.js"></script>-->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-40287225-1', 'github.io');
        ga('send', 'pageview');

    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('.defaultTable').dragtable();

            $('#footerTable').dragtable({excludeFooter:true});

            $('#onlyHeaderTable').dragtable({maxMovingRows:1});

            $('#persistTable').dragtable({persistState:'/someAjaxUrl'});

            $('#handlerTable').dragtable({dragHandle:'.some-handle'});

            $('#constrainTable').dragtable({dragaccept:'.accept'});

            $('#customPersistTable').dragtable({persistState: function(table) {
                    table.el.find('th').each(function(i) {
                        if(this.id != '') {table.sortOrder[this.id]=i;}
                    });
                    $.ajax({url: '/myAjax?hello=world',
                        data: table.sortOrder});
                }
            });

            $('#localStorageTable').dragtable({
                persistState: function(table) {
                    if (!window.sessionStorage) return;
                    var ss = window.sessionStorage;
                    table.el.find('th').each(function(i) {
                        if(this.id != '') {table.sortOrder[this.id]=i;}
                    });
                    ss.setItem('tableorder',JSON.stringify(table.sortOrder));
                },
                restoreState: eval('(' + window.sessionStorage.getItem('tableorder') + ')')
            });

        });
    </script>


    <title>jquery.dragtable.js</title>
</head>
<body>
<h1 id="akottr-header" class="header">
    <div style="float:right;">
        <a href='https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/RegExp' title='JavaScript RegExp .exec'><img src='http://static.jsconf.us/promotejsh.gif' height='75' width='90' alt='JavaScript RegExp .exec'/></a>

    </div>
</h1>
<div id="pageWrap">
    <div id="content" class="main-content">
        <div class="intro">
            I read about dragtable on <a href="http://ajaxian.com/archives/dragtable-drag-and-drop-reorderable-columns-for-an-html-table">ajaxian.com</a>.
            It is a library-independent js-function that gives you the ability to re-order table columns by using drag'n'drop.
            This function does not exactly fulfill my needs. So I thought to myself 'Why not doing it the jQuery way?'
            And my next thought was like in <a href="http://en.wikipedia.org/wiki/Trash_of_the_Titans">Trash of the Titans</a> 'Can't someone else do it?'
            I started google-ing and found nothing. So why not being the garbage man and doing the stuff on my own? That couldn't be that hard.
            There is some really cool stuff growing around jQuery (especially jQuery-ui). So I took the jQuery-core and the
            sortable-Plugin form jQuery-ui and put my stuff on the top of this stack. One weekend and some coffees later this is the result.
            <br />
            Enjoy.
            <br />
            akottr / the garbage man / <span id="e-placeholder">email (mouseover)</span>
            <br />
            <br />
            <a href="https://github.com/akottr/dragtable">fork me on github</a>
        </div>
        <div class="sample">
            <div class="description">
                <h4>description</h4>

                <div class="description-content">Simple table with default options</div>
            </div>
            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
                    <pre><code class="js">$('#defaultTable').dragtable();</code></pre>
                </div>
            </div>

            <div class="demo">
                <h4>demo</h4>
                <div class="demo-content">
                    <table class="defaultTable sar-table">
                        <thead>
                        <tr>
                            <th>TIME</th>
                            <th>%user</th>
                            <th>%nice</th>
                            <th>%system</th>
                            <th>%iowait</th>
                            <th>%idle</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>
                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>

                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="demo">
                <h4>demo (width:100%)</h4>
                <div class="demo-content">
                    <table class="defaultTable sar-table" width="100%">
                        <thead>
                        <tr>
                            <th>TIME</th>

                            <th>%user</th>
                            <th>%nice</th>
                            <th>%system</th>
                            <th>%iowait</th>
                            <th>%idle</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>
                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>

                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>
                <div class="description-content">Table with a fixed footer.</div>

            </div>
            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
                    <pre><code class="js">$('#footerTable').dragtable({excludeFooter:true});</code></pre>
                </div>
            </div>
            <div class="demo">

                <h4>demo</h4>
                <div class="demo-content">
                    <table id="footerTable" class="sar-table">
                        <thead>
                        <tr>
                            <th>TIME</th>
                            <th>%user</th>

                            <th>%nice</th>
                            <th>%system</th>
                            <th>%iowait</th>
                            <th>%idle</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>
                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>

                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">Footer: Some random sar values</td>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>
                <div class="description-content">Moving only the header. Recommended for very large tables (cells &gt; 1000) </div>

            </div>
            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
                    <pre><code class="js">$('#onlyHeaderTable').dragtable({maxMovingRows:1});</code></pre>
                </div>
            </div>
            <div class="demo">

                <h4>demo</h4>
                <div class="demo-content">
                    <table id="onlyHeaderTable" class="sar-table">
                        <thead>
                        <tr>
                            <th>TIME</th>
                            <th>%user</th>

                            <th>%nice</th>
                            <th>%system</th>
                            <th>%iowait</th>
                            <th>%idle</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>
                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>

                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>
                <div class="description-content">Persist your state on the server</div>
            </div>

            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
                    <pre><code class="js">$('#persistTable').dragtable({persistState:'/someAjaxUrl'});</code></pre>
                </div>
            </div>
            <div class="demo">
                <h4>demo</h4>

                <div class="demo-content">
                    <table id="persistTable" class="sar-table">
                        <thead>
                        <tr>
                            <th id="pt_time">TIME</th>
                            <th id="pt_user">%user</th>
                            <th id="pt_nice">%nice</th>

                            <th id="pt_system">%system</th>
                            <th id="pt_wait">%iowait</th>
                            <th id="pt_idle">%idle</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>

                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>
                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>

                <div class="description-content">Maybe you want to sort the columns ascending/descending by clicking into the table-head.
                    No problem! Use a handle to drag'n'drop the columns.
                </div>
            </div>
            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
                    <pre><code class="js">$('#handlerTable').dragtable({dragHandle:'.some-handle'});</code></pre>
                </div>

            </div>
            <div class="demo">
                <h4>demo</h4>
                <div class="demo-content">
                    <table id="handlerTable" class="sar-table">
                        <thead>
                        <tr>
                            <th><div class="some-handle"></div>TIME</th>

                            <th><div class="some-handle"></div>%user</th>
                            <th><div class="some-handle"></div>%nice</th>
                            <th><div class="some-handle"></div>%system</th>
                            <th><div class="some-handle"></div>%iowait</th>
                            <th><div class="some-handle"></div>%idle</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>
                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>

                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>
                <div class="description-content">Allow only some rows to be draggable ('time' is not draggable anymore)</div>
            </div>

            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
                    <pre><code class="js">$('#constrainTable').dragtable({dragaccept:'.accept'});</code></pre>
                </div>
            </div>
            <div class="demo">
                <h4>demo</h4>

                <div class="demo-content">
                    <table id="constrainTable" class="sar-table">
                        <thead>
                        <tr>
                            <th>TIME</th>
                            <th class="accept">%user</th>
                            <th class="accept">%nice</th>

                            <th class="accept">%system</th>
                            <th class="accept">%iowait</th>
                            <th class="accept">%idle</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>

                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>
                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>

                <div class="description-content">Write your own persistence function</div>
            </div>
            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
<pre><code class="js">
$('#customPersistTable').dragtable({persistState: function(table) {
    table.el.find('th').each(function(i) {
      if(this.id != '') {table.sortOrder[this.id]=i;}
    });
    $.ajax({url: '/myAjax?hello=world',
            data: table.sortOrder});
  }
});
</code></pre>
                </div>

            </div>
            <div class="demo">
                <h4>demo</h4>
                <div class="demo-content">
                    <table id="customPersistTable" class="sar-table">
                        <thead>
                        <tr>
                            <th id="cpt_time">TIME</th>

                            <th id="cpt_user">%user</th>
                            <th id="cpt_nice">%nice</th>
                            <th id="cpt_system">%system</th>
                            <th id="cpt_wait">%iowait</th>
                            <th id="cpt_idle">%idle</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>
                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>

                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="sample">
            <div class="description">
                <h4>description</h4>
                <div class="description-content">Write your own persistence function to store your data on the client-side and restore the state onload.
                    Drag some rows and reload this page. The table remains ordered.
                    (does not work in IE6/7 and without a server)</div>
            </div>

            <div class="code">
                <h4>code</h4>
                <div class="code-snippet">
<pre><code class="js">
$('#localStorageTable').dragtable({
    persistState: function(table) {
      if (!window.sessionStorage) return;
      var ss = window.sessionStorage;
      table.el.find('th').each(function(i) {
        if(this.id != '') {table.sortOrder[this.id]=i;}
      });
      ss.setItem('tableorder',JSON.stringify(table.sortOrder));
    },
    restoreState: eval('(' + window.sessionStorage.getItem('tableorder') + ')')
});
</code></pre>
                </div>
            </div>
            <div class="demo">
                <h4>demo</h4>

                <div class="demo-content">
                    <table id="localStorageTable" class="sar-table">
                        <thead>
                        <tr>
                            <th id="lst_time">TIME</th>
                            <th id="lst_user">%user</th>
                            <th id="lst_nice">%nice</th>

                            <th id="lst_system">%system</th>
                            <th id="lst_iowait">%iowait</th>
                            <th id="lst_idle">%idle</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>12:10:01 AM</td><td>28.86</td><td>0.04</td><td>1.65</td><td>0.08</td><td>69.36</td>

                        </tr>
                        <tr>
                            <td>12:20:01 AM</td><td>26.54</td><td>0.00</td><td>1.64</td><td>0.08</td><td>71.74</td>
                        </tr>
                        <tr>
                            <td>12:30:01 AM</td><td>29.73</td><td>0.00</td><td>1.66</td><td>0.09</td><td>68.52</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
