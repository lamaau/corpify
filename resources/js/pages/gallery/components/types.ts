export interface IGallery {
    id: number;
    caption: string;
    sort: number;
    featured: number;
    file: {
        file_url: string;
        file_name: string;
        file_size: string;
        file_mime_type: string;
    };
}
