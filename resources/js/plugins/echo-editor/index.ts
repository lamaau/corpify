import type { App } from "vue";

// @ts-ignore
import EchoEditor from "echo-editor";
import "echo-editor/style.css";

export function setupEchoEditor(app: App) {
    app.use(EchoEditor);
}
