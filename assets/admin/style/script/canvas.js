function drawArrow(fromx, fromy, tox, toy, tickness, color, bidirectional){
    if (typeof bidirectional === 'undeinfed')
        var bidirectional = false;

    //variables to be used when creating the arrow
    var c = this[0];
    var ctx = c.getContext("2d");
    var headlen = 10;

    var angle = Math.atan2(toy-fromy,tox-fromx);
    var angle2 = (Math.atan2(toy-fromy,tox-fromx) * -1);
    console.log(angle2);

    console.log(angle);

    //starting path of the arrow from the start square to the end square and drawing the stroke
    ctx.beginPath();
    ctx.moveTo(fromx, fromy);
    ctx.lineTo(tox, toy);
    ctx.strokeStyle = color;
    ctx.lineWidth = tickness;
    ctx.stroke();

    //starting a new path from the head of the arrow to one of the sides of the point
    ctx.beginPath();
    ctx.moveTo(tox, toy);
    ctx.lineTo(tox-headlen*Math.cos(angle-Math.PI/7),toy-headlen*Math.sin(angle-Math.PI/7));

    //path from the side point of the arrow, to the other side point
    ctx.lineTo(tox-headlen*Math.cos(angle+Math.PI/7),toy-headlen*Math.sin(angle+Math.PI/7));

    //path from the side point back to the tip of the arrow, and then again to the opposite side point
    ctx.lineTo(tox, toy);
    ctx.lineTo(tox-headlen*Math.cos(angle-Math.PI/7),toy-headlen*Math.sin(angle-Math.PI/7));

    //draws the paths created above
    ctx.strokeStyle = color;
    ctx.lineWidth = tickness;
    ctx.stroke();
    ctx.fillStyle = color;
    ctx.fill();

    if (bidirectional) {
        ctx.beginPath();
        ctx.moveTo(fromx, fromy);
        ctx.lineTo(fromx+headlen*Math.cos(angle+Math.PI/7),fromy+headlen*Math.sin(angle+Math.PI/7));

        //path from the side point of the arrow, to the other side point
        ctx.lineTo(fromx+headlen*Math.cos(angle-Math.PI/7),fromy+headlen*Math.sin(angle-Math.PI/7));

        //path from the side point back to the tip of the arrow, and then again to the opposite side point
        ctx.lineTo(fromx, fromy);
        ctx.lineTo(fromx+headlen*Math.cos(angle+Math.PI/7),fromy+headlen*Math.sin(angle+Math.PI/7));

        //draws the paths created above
        ctx.strokeStyle = color;
        ctx.lineWidth = tickness;
        ctx.stroke();
        ctx.fillStyle = color;
        ctx.fill();        
    }
}