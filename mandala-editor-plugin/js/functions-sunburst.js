function minha_cor(current, parent) {
  var pais = parent.ancestors();
  var pai = pais[0];
  var paiName = pai.data.name;
  var avo = pais[1];

  var avoName = "";
  if (avo) {
    avoName = avo.data.name;
  }

  if (paiName == "") {
    return color(current.name);
  } else {
    var cor = minha_cor(pai.data, avo);
    cor = LightenDarkenColor(cor, 20);
    return cor;
  }
}

const vetorHex =  ["#ff0000", 
               "#ffa500", 
               "#ffff00",
               "#008000 ",
               "#0000ff",
              "#800080"];

const color = d3.scaleOrdinal(d3.schemePaired);
//const color = d3.scaleOrdinal(vetorHex);

//var url = "https://raw.githubusercontent.com/lucasrodri/mandala/main/rcc123.json";
var url = "https://raw.githubusercontent.com/lucasrodri/mandala/main/user.json";

fetch(url)
  .then((res) => res.json())
  .then((data) => {
    myChart = Sunburst()
      .data(data)
      .label("name")
      .size("size")
      //.centerRadius('0.1')
      .excludeRoot(true)
      //.color((d, parent) => color(parent ? parent.data.name : null))
      .color((d, parent) => (parent ? minha_cor(d, parent) : "#fff"))
      .onClick(function (d) {
        this.myChart.focusOnNode(d);
        if (d) {
          //if (!d.children) 
          //if (d.children.length == 0) {
          if ((!d.children) && (d.link.length > 0)) {
            //console.log(d.link);
            //window.open(d.link); //Abrir em nova aba
      window.open(d.link,"_self"); //Abrir na mesma aba
          }
        }
    
    currentNode = d;
    
    if ( currentNode === null ||
      (currentNode && currentNode.__dataNode.depth === 0)
    ) {
      this.backButton.classList.add("d-none");
    } else {
      this.backButton.classList.remove("d-none");
    }
    
    
      })
      .tooltipContent(
        (d, node) => `Quantidade de Termos: <i>${node.value}</i>`
      )(document.getElementById("chart"));
  });

function growOpacity(rgba) {
  var opacidade = rgba.split(",").pop().split(")")[0];
  opacidade = opacidade - 0.15;
  var cor = rgba.split(",", 3);
  var corFinal = cor + "," + opacidade + ")";
  return corFinal;
}

function hexToRgbA(hex) {
  var c;
  if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
    c = hex.substring(1).split("");
    if (c.length == 3) {
      c = [c[0], c[0], c[1], c[1], c[2], c[2]];
    }
    c = "0x" + c.join("");
    return (
      "rgba(" + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(",") + ",1)"
    );
  }
  return hex;
}

function LightenDarkenColor(col, amt) {
  var usePound = false;
  if (col[0] == "#") {
    col = col.slice(1);
    usePound = true;
  }

  var num = parseInt(col, 16);

  var r = (num >> 16) + amt;

  if (r > 255) r = 255;
  else if (r < 0) r = 0;

  var b = ((num >> 8) & 0x00ff) + amt;

  if (b > 255) b = 255;
  else if (b < 0) b = 0;

  var g = (num & 0x0000ff) + amt;

  if (g > 255) g = 255;
  else if (g < 0) g = 0;

  return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);
}