export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;

    //購入者情報表示用に追加
    postal_code: string;
    address_1: string;
    address_2: string;
    address_3: string;
    phone_number: string;
}

//購入者情報表示用に追加
export interface Product {
    id: number;
    name: string;
    cost: number;
    image: string;
}
export interface Cart {
    product: Product;
    quantity: number;
}

//フラッシュメッセージ用に追加
export interface FlashMessage {
    message?: string;
}
export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    //フラッシュメッセージ用に追加
    flash?: FlashMessage; 
    //購入者情報表示用に追加
    carts?: Cart[];
};
