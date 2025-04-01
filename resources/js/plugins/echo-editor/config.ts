import {
    Bold,
    BulletList,
    Italic,
    BaseKit,
    Underline,
    Strike,
    LineHeight,
    Image,
    History,
    Heading,
    CodeBlock,
    FontSize,
    Table,
    Clear,
    Blockquote,
    Link,
    Video,
    OrderedList,
    HorizontalRule,
    Fullscreen,
    TaskList,
    MoreMark,
    FormatPainter,
    SlashCommand,
    Indent,
    Columns,
    TextAlign,
    ImageUpload,
    VideoUpload,
    FontFamily,
    FindAndReplace,
    Code,
    Preview,
    Iframe,
    locale,
} from "echo-editor";

export const config = {
    setUp: () => locale.setLang("en"),
    extensions: [
        BaseKit.configure({
            placeholder: {
                showOnlyCurrent: true,
            },
            characterCount: {
                limit: 50000,
            },
        }),
        History,
        Columns,
        FormatPainter,
        Clear,
        Heading.configure({ spacer: true }),
        FontSize,
        FontFamily,
        Bold,
        Italic,
        Underline,
        Strike,
        MoreMark,
        BulletList,
        OrderedList,
        TextAlign.configure({
            types: ["heading", "paragraph", "image"],
            spacer: true,
        }),
        Indent,
        LineHeight,
        TaskList.configure({
            spacer: true,
            taskItem: {
                nested: true,
            },
        }),
        Link,
        Image,
        ImageUpload.configure({
            upload: (files: File) => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve(URL.createObjectURL(files));
                    }, 3000);
                });
            },
        }),
        Video,
        VideoUpload.configure({
            upload: async function handleFileUpload(files: File[]) {
                const f = files.map((file) => ({
                    src: URL.createObjectURL(file),
                    alt: file.name,
                }));
                return Promise.resolve(f);
            },
        }),
        Blockquote,
        SlashCommand,
        HorizontalRule,
        Fullscreen.configure({ spacer: true }),
        CodeBlock,
        Table,
        Code,
        FindAndReplace.configure({ spacer: true }),
        Preview,
        Iframe,
    ] as const,
};
