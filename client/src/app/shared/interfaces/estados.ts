export interface Estados {
    id: number;
    nome: string;
    uf: string;
    pais: number;
}

export interface Cidades {
    id: number;
    nome: string;
    estado: number;
}
