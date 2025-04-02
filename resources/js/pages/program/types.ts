export interface IFeature {
    feature_name: string;
    icon: string;
}

export interface IProgram {
    id: number;
    name: string;
    summary?: string;
    features: IFeature[];
}
