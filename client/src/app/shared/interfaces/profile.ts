export interface Profile {
    id: number;
    nome: string;
    cpf: string;
    data_nasc: Date;
    telefone: string;
    genero: string;
    endereco: string;
    bairro: string;
    complemento: string;
    cidade: string;
    estado: string;
    cep: string;
    email: string;
    senha: string;
    last_login: Date;
    role: number;
    reset_token: string;
    created_at: Date;
    updated_at: Date;
    is_blocked: number;
    status: boolean;
    message: string;
}
