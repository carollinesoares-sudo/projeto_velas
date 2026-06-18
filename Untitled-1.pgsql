
DROP TABLE IF EXISTS public.velas CASCADE;


CREATE TABLE public.velas (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    aroma VARCHAR(50) NOT NULL,
    tamanho VARCHAR(20) NOT NULL,
    cor VARCHAR(30),
    preco DECIMAL(10,2) NOT NULL CHECK (preco > 0),
    estoque INT NOT NULL DEFAULT 0 CHECK (estoque >= 0),
    descricao TEXT NOT NULL,
    criado_em TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 3. Insere as 12 velas (todas as aspas, parênteses e vírgulas revisados)
INSERT INTO public.velas (nome, aroma, tamanho, cor, preco, estoque, descricao) VALUES
('Bergamota Fresca', 'Bergamota & Lima', '250g', 'Verde Oliva', 72.00, 20, 'Fragrância cítrica, fresca e revigorante para trazer energia e foco ao seu dia.'),
('Capim-Limão Real', 'Capim-Limão', '150g', 'Verde Claro', 38.50, 3, 'Nota herbal extremamente refrescante. Perfeita para criar uma atmosfera de spa em casa.'),
('Brisa de Verão', 'Laranja Doce & Alecrim', '200g', 'Amarelo Pastel', 52.00, 12, 'Combinação radiante que estimula a criatividade e alegra o ambiente.'),
('Lavanda de Campo', 'Lavanda pura', '150g', 'Lilás', 40.00, 4, 'Aroma relaxante ideal para desacelerar, acalmar a mente e criar um ambiente de paz.'),
('Jardim de Jasmim', 'Jasmim & Neroli', '200g', 'Branco Off-white', 58.00, 18, 'Fragrância floral rica e sofisticada, trazendo uma sensação de jardim florido na primavera.'),
('Flor de Cerejeira', 'Cherry Blossom', '100g', 'Rosa Chá', 32.00, 0, 'Aroma delicado, sutilmente adocicado e floral, evocando renovação e leveza.'),
('Autumn Embers', 'Canela e Cravo', '200g', 'Marrom Terroso', 55.00, 15, 'Uma fragrância calorosa que remete ao aconchego do outono com notas amadeiradas.'),
('Sândalo Sagrado', 'Sândalo & Cedro', '250g', 'Terracota', 75.00, 8, 'Aroma amadeirado profundo e marcante, excelente para momentos de meditação.'),
('Floresta de Pinheiro', 'Pinheiro & Nozes', '200g', 'Verde Musgo', 56.00, 2, 'Fragrância resinosa e fresca que remete ao ar puro de uma floresta montanhosa.'),
('Baunilha Confort', 'Vanilla Premium', '150g', 'Creme', 45.00, 25, 'Fragrância clássica aconchegante que abraça o ambiente com notas quentes de fava de baunilha.'),
('Café da Manhã', 'Café Torrado & Caramelo', '100g', 'Marrom Escuro', 35.00, 10, 'Aroma nostálgico e estimulante de café fresco com um toque cremoso de caramelo.'),
('Doce de Leite Artesanal', 'Doce de Leite', '200g', 'Caramelo', 54.00, 5, 'Fragrância gourmand densa e reconfortante, inspirada nas receitas tradicionais de doces caseiros.');

SELECT * FROM public.velas ORDER BY nome ASC;