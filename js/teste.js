// Seletores
const canvas = document.querySelector(".canvas");
const inputSize = document.querySelector(".input-size");
const inputColor = document.querySelector(".input-color");
const usedColors = document.querySelector(".used-colors");
const buttonSave = document.querySelector(".button-save");
const colResize = document.querySelector(".resize");
const main = document.querySelector("main");

const MIN_CANVAS_SIZE = 4;

let isPainting = false;
let isResizing = false;

// Função utilitária para criar elementos
const createElement = (tag, className = "") => {
    const element = document.createElement(tag);
    element.className = className;
    return element;
};

// Define a cor do pixel
const setPixelColor = (pixel) => {
    pixel.style.backgroundColor = inputColor.value;
};

// Cria um único pixel
const createPixel = () => {
    const pixel = createElement("div", "pixel");

    // Eventos para pintura
    pixel.addEventListener("mousedown", () => setPixelColor(pixel));
    pixel.addEventListener("mouseover", () => {
        if (isPainting) setPixelColor(pixel);
    });

    return pixel;
};

// Carrega o canvas (cria a grade de pixels)
const loadCanvas = () => {
    const length = inputSize.value;
    canvas.innerHTML = ""; // Limpa o canvas

    // Define o estilo da grade CSS (necessário para redimensionamento responsivo)
    canvas.style.gridTemplateColumns = `repeat(${length}, 1fr)`;

    for (let i = 0; i < length; i += 1) {
        const row = createElement("div", "row");

        for (let j = 0; j < length; j += 1) {
            row.append(createPixel());
        }

        canvas.append(row);
    }
};

// Atualiza o tamanho do canvas ao mudar o input
const updateCanvasSize = () => {
    if (inputSize.value >= MIN_CANVAS_SIZE) {
        loadCanvas();
    }
};

// Adiciona a cor atual à lista de cores usadas
const changeColor = () => {
    const button = createElement("button", "button-color");
    const currentColor = inputColor.value;

    button.style.backgroundColor = currentColor;
    button.setAttribute("data-color", currentColor);
    button.addEventListener("click", () => (inputColor.value = currentColor));

    const savedColors = Array.from(usedColors.children);

    const check = (btn) => btn.getAttribute("data-color") !== currentColor;

    if (savedColors.every(check)) {
        usedColors.append(button);
    }
};

// Lógica de redimensionamento do canvas (com a barra lateral)
const resizeCanvas = (cursorPositionX) => {
    if (!isResizing) return;

    const canvasOffset = canvas.getBoundingClientRect().left;
    const width = `${cursorPositionX - canvasOffset - 20}px`;

    canvas.style.maxWidth = width;
    colResize.style.height = width;
};

// FUNÇÃO CRÍTICA: Salva o canvas em PNG
const saveCanvas = () => {
    // 1. Aplica a classe de salvamento (remove a grade, muda o fundo)
    document.body.classList.add('saving-mode'); 
    
    // Oculta temporariamente a área de redimensionamento
    colResize.style.display = 'none'; 

    // O html2canvas precisa que a imagem seja capturada dentro da div do canvas.
    html2canvas(canvas, {
        onrendered: (image) => {
            const img = image.toDataURL("image/png");
            const link = createElement("a");

            link.href = img;
            link.download = "pixelart.png";

            link.click();
            
            // 2. Restaura o modo normal após o salvamento
            document.body.classList.remove('saving-mode'); 
            colResize.style.display = 'block'; 
        },
    });
};

// Event Listeners Globais
canvas.addEventListener("mousedown", () => (isPainting = true));
// Adiciona o listener no document para capturar o mouseup fora do canvas
document.addEventListener("mouseup", () => (isPainting = false)); 

inputSize.addEventListener("change", updateCanvasSize);
inputColor.addEventListener("change", changeColor);

colResize.addEventListener("mousedown", () => (isResizing = true));

main.addEventListener("mouseup", () => (isResizing = false));
main.addEventListener("mousemove", ({ clientX }) => resizeCanvas(clientX));

buttonSave.addEventListener("click", saveCanvas);

// Carrega o canvas ao iniciar a página
loadCanvas();
