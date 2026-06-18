
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
    imagem VARCHAR(255), 
    criado_em TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO public.velas (nome, aroma, tamanho, cor, preco, estoque, descricao, imagem) VALUES
('Bergamota Fresca', 'Bergamota & Lima', '250g', 'Verde Oliva', 72.00, 20, 'Fragrância cítrica.', 'bergamota.jpg'),
('Capim-Limão Real', 'Capim-Limão', '150g', 'Verde Claro', 38.50, 3, 'Nota herbal.', 'capim_limao.jpg'),
('Lavanda de Campo', 'Lavanda pura', '150g', 'Lilás', 40.00, 4, 'Aroma relaxante.', 'lavanda.jpg'),
('Sândalo Sagrado', 'Sândalo & Cedro', '250g', 'Terracota', 75.00, 8, 'Aroma amadeirado.', 'sandalo.jpg'),
('Café da Manhã', 'Café Torrado & Caramelo', '100g', 'Marrom Escuro', 35.00, 10, 'Aroma nostálgico de café.', 'cafe.jpg'),
('Doce de Leite Artesanal', 'Doce de Leite', '200g', 'Caramelo', 54.00, 5, 'Fragrância gourmand.', 'doce_de_leite.jpg');