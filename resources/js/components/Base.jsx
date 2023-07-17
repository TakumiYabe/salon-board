// index.js などのアプリケーションのエントリーポイントで行う例
import React from 'react';
import {createRoot} from "react-dom/client";
import { createTheme, ThemeProvider } from '@mui/material/styles';
import Password from './Password.jsx';

// デフォルトテーマをカスタマイズ
const theme = createTheme({
    components: {
        MuiDialog: {
            styleOverrides: {
                // Dialogのデフォルトスタイルを無効化
                root: {
                    all: 'unset',
                },
            },
        },
    },
});

createRoot(document.getElementById('dialog-register-password').render(
    <React.StrictMode>
        {/* カスタムテーマをThemeProviderでアプリケーション全体に適用 */}
        <ThemeProvider theme={theme}>
            <Password />
        </ThemeProvider>
    </React.StrictMode>
));

