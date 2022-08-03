<?php 
    require 'includes/database.php';
    require 'includes/functions.php';
    $conn = getDB();
    $topFive = getTop($conn);
    $languages = getLang($conn);

?>
<?php require 'includes/header.php'; ?>
    <div class="container">
        <canvas id="popular"></canvas>
    </div>

    <div class="container">
        <canvas id="language"></canvas>
    </div>

    <div id="barModal" class="modal-background">
        <div class="modal">
            <table id="table" class="table">
                <thead id="head"></thead>
                <tbody id="body"></tbody>
            </table>
            <div class="modal-footer"><button class="btn danger" onclick="modalClose()">Exit</button></div>
        </div>
    </div>

    <script>
        const barModal = document.getElementById('barModal')
        barModal.classList.toggle('hide')
        let pop = document.getElementById('popular').getContext('2d')
        let config = {}
        let popularChart = new Chart(pop, config);

        function modalClose() {
            barModal.classList.toggle('hide')
            $('#table').DataTable().destroy()

            document.getElementById("body").remove()
            document.getElementById("head").remove()

            var b = document.createElement('tbody');
            var h = document.createElement('thead');
            b.setAttribute("id", "body");
            h.setAttribute("id", "head");
            document.getElementById('table').appendChild(b)
            document.getElementById('table').appendChild(h)
        }

        makePopular()
        function makePopular() {
            Chart.defaults.global.defaultFontFamily = 'Arial';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#777';

            popularChart = new Chart(pop, {
                type: 'bar', //bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {
                    labels: [<?php for ($i = 0; $i < 5; $i++) { echo "\"" . $topFive[$i]['title'] . "\"" . ', '; } ?>],
                    datasets: [
                        {
                            label: "popularity",
                            backgroundColor: ["#F26627", "#F9A26C", "#EFEEEE", "#9BD7D1", "#325D79"],
                            data: [<?php for ($i = 0; $i < 5; $i++) { echo "\"" . $topFive[$i]['vote_count'] . "\"" . ', '; }?>]
                        }
                    ]
                },
                options: {
                    onClick: (e) => {
                        $(document).ready( function () {
                            $('#table').DataTable().destroy();
                        } );
                        // $('#table').DataTable().destroy()
                        // modalClose();
                        barModal.classList.remove('hide')

                        document.getElementById("body").remove()
                        document.getElementById("head").remove()

                        var b = document.createElement('tbody');
                        var h = document.createElement('thead');
                        b.setAttribute("id", "body");
                        h.setAttribute("id", "head");
                        document.getElementById('table').appendChild(b)
                        document.getElementById('table').appendChild(h)

                        var headroot = document.createElement('tr')
                        var headarr = []
                        var i = 0
                        <?php foreach($topFive[0] as $index => $topFivearr) { 
                            if ($index == 'backdrop_path' || $index == 'poster_path' || $index == 'description') {
                                continue;
                            } else { ?>
                            headarr[i] = document.createElement('th');
                            headarr[i].innerText = "<?= $index ?>"
                            i++;
                        <?php } } ?>

                        headarr.forEach(x => headroot.append(x));
                        document.getElementById('head').appendChild(headroot);

                        <?php 
                        $i = 0;
                        foreach($topFive as $movie) { ?>
                            var root = document.createElement('tr')
                            var arr = [];
                            var len = 0;
                            <?php foreach($topFive[$i] as $index) { 
                                if($index[0] == "/") {
                                    continue;
                                } else { ?>
                                arr[len] = document.createElement('th')
                                arr[len].textContent = <?= "\"" . $index . "\"";?>;
                                len = len +1
                            <?php } } ?>
                            <?php $i++; ?> 
                            arr.forEach(x => root.append(x))
                            document.getElementById('body').appendChild(root)
                        <?php } ?>
                        $(document).ready( function () {
                            $('#table').DataTable({
                                'order': [5, 'desc']
                            });
                        } );
                    },
                    title: {
                        display: true,
                        text: `Top Movies This Month`,
                        fontSize: 25
                    },
                    legend: {
                        display: 'false',
                        position: 'right',
                        labels: {
                            fontColor: 'black'
                        }
                    },
                    layout: {
                        padding: {
                            left: 50,
                            right: 0,
                            bottom: 0,
                            top: 0
                        }
                    },
                    tooltips: {
                        enabled: true
                    }
                }
            })
        }

        let lang = document.getElementById('language').getContext('2d')
        let languageChart = new Chart(lang, config);

        makeLang()
        function makeLang() {
            Chart.defaults.global.defaultFontFamily = 'Arial';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#777';

            languageChart = new Chart(lang, {
                type: 'pie', //bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {
                    labels: [<?php foreach($languages as $lang) { echo "\"" . $lang['original_language'] . "\", "; } ?>],
                    datasets: [
                        {
                            label: "Language",
                            backgroundColor: ["teal", "blue", "pink", "magenta"],
                            data: [<?php foreach($languages as $count) { echo  $count['COUNT(*)'] . ", "; } ?>]
                        }
                    ]
                },
                options: {
                    onClick: (e) => {
                        
                        const points = languageChart.getElementsAtEventForMode(e, 'nearest', {intersect: true}, true);
                        if (points[0]) {

                            document.getElementById("body").remove()
                            document.getElementById("head").remove()

                            var b = document.createElement('tbody');
                            var h = document.createElement('thead');
                            b.setAttribute("id", "body");
                            h.setAttribute("id", "head");
                            document.getElementById('table').appendChild(b)
                            document.getElementById('table').appendChild(h)

                            $(document).ready( function () {
                                $('#table').DataTable().destroy();
                            } );

                            barModal.classList.remove('hide')
                            const index = points[0]._index
                            const label = languageChart.data.labels[index]
                            <?php 
                                $sql = "SELECT title, release_date, original_language, popularity, vote_count FROM movies";
                                $results = mysqli_query($conn, $sql);
                                $res = mysqli_fetch_all($results, MYSQLI_ASSOC);
                            ?>
                            var headroot = document.createElement('tr')
                            var headarr = []
                            var i = 0
                            <?php foreach($res[0] as $index => $langres) { 
                                if ($index == 'backdrop_path' || $index == 'poster_path' || $index == 'description') {
                                    continue;
                                } else { ?>
                                headarr[i] = document.createElement('th');
                                headarr[i].innerText = "<?= $index ?>"
                                i++;
                            <?php } } ?>

                            headarr.forEach(x => headroot.append(x));
                            document.getElementById('head').appendChild(headroot);

                            <?php 
                            $i = 0;
                            foreach($res as $movie) { ?>
                                var root = document.createElement('tr')
                                var arr = [];
                                var len = 0;
                                <?php foreach($res[$i] as $index) { 
                                    if($index[0] == "/") {
                                        continue;
                                    } else { ?>
                                    arr[len] = document.createElement('th')
                                    arr[len].textContent = <?= "\"" . $index . "\"";?>;
                                    len = len +1
                                <?php } } ?>
                                <?php $i++; ?> 
                                arr.forEach(x => root.append(x))
                                document.getElementById('body').appendChild(root)
                            <?php } ?>

                            $(document).ready( function () {
                                $('#table').DataTable().columns(2).search(label).draw();
                            } );
                        }
                    },
                    title: {
                        display: true,
                        text: `Top Languages`,
                        fontSize: 25
                    },
                    legend: {
                        display: 'false',
                        position: 'right',
                        labels: {
                            fontColor: 'black'
                        }
                    },
                    layout: {
                        padding: {
                            left: 50,
                            right: 0,
                            bottom: 0,
                            top: 0
                        }
                    },
                    tooltips: {
                        enabled: true
                    }
                }
            })
        }
    </script>
    <script>
            
    </script>
