export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
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
};
