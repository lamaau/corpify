export interface IRegulation {
    id: number;
    title: string;
    file: string;
    summary?: string;
}

export interface IRowProps {
    row: IRegulation;
}
