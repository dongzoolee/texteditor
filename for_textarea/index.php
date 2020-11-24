<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDCD</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.0/socket.io.js"></script>
    <script src="caret.js"></script>
    <!-- <script src="codemirror/lib/codemirror.js"></script>
    <link rel="stylesheet" href="codemirror/lib/codemirror.css">
    <script src="codemirror/addon/edit/matchbrackets.js"></script>
    <script src="codemirror/clike.js"></script> -->
    <style>
        #code {
            -moz-appearance: textfield-multiline;
            -webkit-appearance: textarea;
            border: 1px solid gray;
            font: medium -moz-fixed;
            font: -webkit-small-control;
            height: 400px;
            overflow: auto;
            padding: 2px;
            resize: both;
            width: 400px;
        }
    </style>
</head>

<body>
    <textarea id="code">
    </textarea>
    <script>
        //codemirror
    //     var cppEditor = CodeMirror.fromTextArea(document.getElementById("code"), {
    //     lineNumbers: true,
    //     matchBrackets: true,
    //     mode: "text/x-c++src"
    //   });

        var socket = io("https://groupskt.leed.at");
        socket.on('connect', () => {
            socket.on('welcome', (msg) => {
                $('#code').val(msg);
            })
        })
        $('#code').on('click change keydown paste input focus', (e) => {
            var code = document.getElementById('code');
            socket.emit('code', $('#code').val());
        });
        socket.on('code edit', (msg) => {
            console.log(msg);
            $('#code').val(msg);
        })

        //https://github.com/component/textarea-caret-position
        $('#code').on('click change keydown paste input focus', (e) => {
            var pos = getCaretCoordinates(document.getElementById('code'), document.getElementById('code').selectionEnd);///;이걸잡아야댐
            socket.emit('pos', pos);
        });
        socket.on('cng pos', (data) => {
            console.log('---cng pos---');
            var code = document.getElementById('code');
            var elem = document.getElementById(data.id);
            var a = (parseInt(code.offsetTop) -
                parseInt(code.scrollTop) +
                parseInt(data.top));
            var b = (parseInt(code.offsetLeft) -
                parseInt(code.scrollLeft) +
                parseInt(data.left));
            elem.style.left = b + 'px';
            elem.style.top = a + 'px';
            console.log(elem.style.left + ' ' + elem.style.top)
        })
        socket.on('new pos', (data) => {
            console.log('---new pos---');
            var element = document.getElementById('code');
            var fontSize = getComputedStyle(element).getPropertyValue('font-size');

            // div 만들고 body에 넣음
            var rect = document.createElement('div');
            rect.id = data.id;
            document.body.appendChild(rect);

            rect.style.position = 'absolute';
            rect.style.backgroundColor = 'green';
            rect.style.height = fontSize;
            rect.style.width = '1px';
            var a = (parseInt(element.offsetTop) -
                parseInt(element.scrollTop) +
                parseInt(data.top));
            var b = (parseInt(element.offsetLeft) -
                parseInt(element.scrollLeft) +
                parseInt(data.left));
            rect.style.left = b + 'px';
            rect.style.top = a + 'px';
            console.log(rect.style.left + ' ' + rect.style.top)
        })
        socket.on('disconn', (id) => {
            $('#' + id).remove();
        })
        socket.on('caret welcome', (json) => {
            for (key in json.arr) {
                if (json.id == key) continue;


                var element = document.getElementById('code');
                var fontSize = getComputedStyle(element).getPropertyValue('font-size');
                var rect = document.createElement('div');
                rect.id = key;
                document.body.appendChild(rect);
                rect.style.position = 'absolute';
                rect.style.backgroundColor = 'green';
                rect.style.height = fontSize;
                rect.style.width = '1px';
                var a = (parseInt(element.offsetTop) -
                    parseInt(element.scrollTop) +
                    parseInt(json.arr[key][1]));
                var b = (parseInt(element.offsetLeft) -
                    parseInt(element.scrollLeft) +
                    parseInt(json.arr[key][0]));
                rect.style.left = b + 'px';
                rect.style.top = a + 'px';
            }
        })

        function colorRand(){
            var colorCode = "#"+Math.round(Math.random()*0xffffff).toString(16);
            return colorCode;
        }
    </script>
</body>

</html>