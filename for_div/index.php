<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDCD</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.0/socket.io.js"></script>
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
    <div id="code" contenteditable>

    </div>
    <script>
        var socket = io("https://groupskt.leed.at");
        socket.on('connect', () => {
            socket.on('welcome', (msg) => {
                $('#code').html(msg);
            })
        })
        $('#code').on('click change keydown paste input focus', (e) => {
            var code = document.getElementById('code');
            socket.emit('code', $('#code').html());
            // e.preventDefault();
            // console.log(document.getSelection().anchorOffset + " " + document.getSelection().focusOffset);
            // console.log(getCaretClientPosition());
        });
        socket.on('code edit', (msg) => {
            console.log(msg);
            $('#code').html(msg);
        })

        // function outputsize() {
        //     socket.emit('outerHtml', document.getElementById('code').outerHTML);
        // }
        // outputsize()

        // new ResizeObserver(outputsize).observe(code);
        // socket.on('outerHtml', (msg) => {
        //     console.log(msg);
        //     document.getElementById('code').outerHTML = msg;
        // })

        // 3
        // var editor = null;
        // var output = null;

        // const getTextSelection = function(editor) {
        //     const selection = window.getSelection();

        //     if (selection != null && selection.rangeCount > 0) {
        //         const range = selection.getRangeAt(0);

        //         return {
        //             start: getTextLength(editor, range.startContainer, range.startOffset),
        //             end: getTextLength(editor, range.endContainer, range.endOffset)
        //         };
        //     } else
        //         return null;
        // }

        // const getTextLength = function(parent, node, offset) {
        //     var textLength = 0;

        //     if (node.nodeName == '#text')
        //         textLength += offset;
        //     else
        //         for (var i = 0; i < offset; i++)
        //             textLength += getNodeTextLength(node.childNodes[i]);

        //     if (node != parent)
        //         textLength += getTextLength(parent, node.parentNode, getNodeOffset(node));

        //     return textLength;
        // }

        // const getNodeTextLength = function(node) {
        //     var textLength = 0;

        //     if (node.nodeName == 'BR')
        //         textLength = 1;
        //     else if (node.nodeName == '#text')
        //         textLength = node.nodeValue.length;
        //     else if (node.childNodes != null)
        //         for (var i = 0; i < node.childNodes.length; i++)
        //             textLength += getNodeTextLength(node.childNodes[i]);

        //     return textLength;
        // }

        // const getNodeOffset = function(node) {
        //     return node == null ? -1 : 1 + getNodeOffset(node.previousSibling);
        // }

        // window.onload = function() {
        //     editor = document.querySelector('#code');
        //     output = document.querySelector('#output');

        //     document.addEventListener('selectionchange', handleSelectionChange);
        // }

        // const handleSelectionChange = function() {
        //     if (isEditor(document.activeElement)) {
        //         const textSelection = getTextSelection(document.activeElement);

        //         if (textSelection != null) {
        //             const text = document.activeElement.innerText;
        //             const selection = text.slice(textSelection.start, textSelection.end);
        //             print(`Selection: [${selection}] (Start: ${textSelection.start}, End: ${textSelection.end})`);
        //         } else
        //             print('Selection is null!');
        //     } else
        //         print('Select some text above');
        // }

        // const isEditor = function(element) {
        //     // return element != null && element.idList.contains('code');
        //     return element != null && element.id == 'code';
        // }

        // const print = function(message) {
        //     if (output != null)
        //         output.innerText = message;
        //     else
        //         console.log('output is null!');
        // }
        // 4
        // function getCaretClientPosition() {
        //     var x = 0,
        //         y = 0;
        //     var sel = window.getSelection();
        //     if (sel.rangeCount) {

        //         var range = sel.getRangeAt(0);
        //         var needsToWorkAroundNewlineBug = (range.startContainer.nodeName.toLowerCase() == 'p' &&
        //             range.startOffset == 0);

        //         if (needsToWorkAroundNewlineBug) {
        //             x = range.startContainer.offsetLeft;
        //             y = range.startContainer.offsetTop;
        //         } else {
        //             if (range.getClientRects) {
        //                 var rects = range.getClientRects();
        //                 if (rects.length > 0) {
        //                     x = rects[0].left;
        //                     y = rects[0].top;
        //                 }
        //             }
        //         }
        //     }
        //     return {
        //         x: x,
        //         y: y
        //     };
        // }
        // 5 caret.js
        var element = document.getElementById('code');
        var fontSize = getComputedStyle(element).getPropertyValue('font-size');

        var rect = document.createElement('div');
        document.body.appendChild(rect);
        rect.style.position = 'absolute';
        //rect.style.backgroundColor = 'red';
        rect.style.height = fontSize;
        rect.style.width = '1px';

        function update(coordinates) {
            // Set `debug` to true in order to see the mirror div. Default false.
            //  console.log(getCaretCharacterOffsetWithin(document.getElementById('code')));
            //  var coordinates = getCaretCoordinates(element, getCaretCharacterOffsetWithin(document.getElementById('code')), {
            //      debug: true
            //  });
            console.log('(top, left) = (%s, %s)', coordinates.top, coordinates.left);
            var a = (parseInt(element.offsetTop) -
                parseInt(element.scrollTop) +
                parseInt(coordinates.top));
            rect.style.top = a + 'px';
            var b = (parseInt(element.offsetLeft) -
                parseInt(element.scrollLeft) +
                parseInt(coordinates.left));
            rect.style.left = b + 'px';
            console.log(a + ' ' + b);
        }
        // 6
        // function getCaretCharacterOffsetWithin(element) {
        //     var caretOffset = 0;
        //     if (typeof window.getSelection != "undefined") {
        //         var range = window.getSelection().getRangeAt(0);
        //         var preCaretRange = range.cloneRange();
        //         preCaretRange.selectNodeContents(element);
        //         preCaretRange.setEnd(range.endContainer, range.endOffset);
        //         caretOffset = preCaretRange.toString().length;
        //         //caretOffset = preCaretRange.startContainer.innerText.replaceAll(/\n/gi,' ').length;
        //         console.log(preCaretRange);
        //     } else if (typeof document.selection != "undefined" && document.selection.type != "Control") {
        //         var textRange = document.selection.createRange();
        //         var preCaretTextRange = document.body.createTextRange();
        //         preCaretTextRange.moveToElementText(element);
        //         preCaretTextRange.setEndPoint("EndToEnd", textRange);
        //         caretOffset = preCaretTextRange.text.length;
        //         console.log(preCaretTextRange);
        //     }
        //     return caretOffset;
        // }
        // 7
        var getCaretPixelPos = function($node, offsetx, offsety) {
            offsetx = offsetx || 0;
            offsety = offsety || 0;

            var nodeLeft = 0,
                nodeTop = 0;
            if ($node) {
                nodeLeft = $node.offsetLeft;
                nodeTop = $node.offsetTop;
            }

            var pos = {
                left: 0,
                top: 0
            };

            if (document.selection) {
                var range = document.selection.createRange();
                pos.left = range.offsetLeft + offsetx - nodeLeft + 'px';
                pos.top = range.offsetTop + offsety - nodeTop + 'px';
            } else if (window.getSelection) {
                var sel = window.getSelection();
                var range = sel.getRangeAt(0).cloneRange();
                try {
                    range.setStart(range.startContainer, range.startOffset - 1);
                } catch (e) {}
                var rect = range.getBoundingClientRect();
                if (range.endOffset == 0 || range.toString() === '') {
                    // first char of line
                    if (range.startContainer == $node) {
                        // empty div
                        if (range.endOffset == 0) {
                            pos.top = '0px';
                            pos.left = '0px';
                        } else {
                            // firefox need this
                            var range2 = range.cloneRange();
                            range2.setStart(range2.startContainer, 0);
                            var rect2 = range2.getBoundingClientRect();
                            pos.left = rect2.left + offsetx - nodeLeft + 'px';
                            pos.top = rect2.top + rect2.height + offsety - nodeTop + 'px';
                        }
                    } else {
                        pos.top = range.startContainer.offsetTop + 'px';
                        pos.left = range.startContainer.offsetLeft + 'px';
                    }
                } else {
                    pos.left = rect.left + rect.width + offsetx - nodeLeft + 'px';
                    pos.top = rect.top + offsety - nodeTop + 'px';
                }
            }
            return pos;
        };

        $('#code').on('click change keydown paste input focus', (e) => {
            var pos = getCaretPixelPos($('#code')[0]);
            update(pos);
            socket.emit('pos', pos);
            // $edit.mouseup(function() {
            //var pos = getCaretPixelPos($('#code')[0]);
            //console.log('x : ' + pos.left + ', y : ' + pos.top);
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
    </script>
</body>

</html>