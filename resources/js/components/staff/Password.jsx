import React, {Fragment, useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
import {createRoot} from "react-dom/client";
import axios from 'axios';
import Dialog from '@mui/material/Dialog';
import TextField from '@mui/material/TextField';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import Button from '@mui/material/Button';

function Password() {
    const isNew = Boolean($('#dialog-register-password').data('isNew'));
    const staffId = $('#staff_id').data('staff-id')

    const [isOpen, setIsOpen] = useState(false);
    const [error, setError] = useState('');
    const [data, setData] = useState({
        id: staffId,
        current_password: '',
        password: '',
        password_confirmation: ''
    });
    const handleOpen = () => {
        setIsOpen(true);
    };

    const handleClose = () => {
        setIsOpen(false);
        setError('');
        setData([]);
    };

    const handleChange = (event) => {
        const {name, value} = event.target;
        setData({...data, [name]: value});
    };

    const updatePassword = async () => {
        await axios
            .post('/api/staffs/updatePassword', {
                    id: data.id,
                    current_password: data.current_password,
                    password: data.password,
                    password_confirmation: data.password_confirmation
                },
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
            .then((response) => {
                if (response.data.error) {
                    setError(response.data.error);
                } else {
                    handleClose();
                }
            }).catch(error => {
                console.log(error);
            })
    }

    return (
        <Fragment>
            <Button onClick={handleOpen} className="system-button button-middle" disabled={isNew}>パスワードを変更</Button>
            <Dialog open={isOpen} onClose={handleClose}>
                <DialogTitle>パスワードの変更</DialogTitle>
                <DialogContent>
                    <div class="error-message">{error}</div>
                    <div>
                        <p class="caution">パスワードは8文字以上20文字以内で登録してください。</p>
                    </div>
                    <div>
                        <div>
                            <TextField
                                margin="dense"
                                id="current_password"
                                name="current_password"
                                label="現在のパスワード"
                                type="password"
                                value={data.current_password}
                                required="required"
                                InputProps={{
                                    inputProps: {
                                        max: 20,
                                    },
                                }}
                                onChange={handleChange}
                            />
                        </div>
                        <div>
                            <TextField
                                margin="dense"
                                id="password"
                                name="password"
                                label="新しいパスワード"
                                type="password"
                                value={data.password}
                                required="required"
                                InputProps={{
                                    inputProps: {
                                        max: 20,
                                    },
                                }}
                                onChange={handleChange}
                            />
                        </div>
                        <div>
                            <TextField
                                margin="dense"
                                id="password_confirmation"
                                name="password_confirmation"
                                label="新しいパスワード（確認用）"
                                type="password"
                                value={data.password_confirmation}
                                required="required"
                                InputProps={{
                                    inputProps: {
                                        max: 20,
                                    },
                                }}
                                onChange={handleChange}
                            />
                        </div>
                    </div>
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleClose}>閉じる</Button>
                    <Button href="#" onClick={updatePassword}>更新する</Button>
                </DialogActions>
            </Dialog>
        </Fragment>
    );
}

export default Password;

if (document.getElementById('dialog-register-password')) {
    createRoot(document.getElementById('dialog-register-password')).render(<Password/>);
}
