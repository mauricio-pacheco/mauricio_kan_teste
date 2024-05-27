import React, { useReducer, useEffect } from 'react';
import PreLoader from './PreLoader';
import FileList from './FileList';

const initialState = {
    file: null,
    uploadMessage: null,
    uploading: false,
    csrfToken: '',
    uploadProgress: 0,
    files: [],
    updateFiles: false
};

function reducer(state, action) {
    switch (action.type) {
        case 'SET_FILE':
            return { ...state, file: action.payload };
        case 'SET_UPLOAD_MESSAGE':
            return { ...state, uploadMessage: action.payload };
        case 'SET_UPLOADING':
            return { ...state, uploading: action.payload };
        case 'SET_CSRF_TOKEN':
            return { ...state, csrfToken: action.payload };
        case 'SET_UPLOAD_PROGRESS':
            return { ...state, uploadProgress: action.payload };
        case 'SET_FILES':
            return { ...state, files: action.payload };
        case 'SET_UPDATE_FILES':
            return { ...state, updateFiles: action.payload };
        default:
            return state;
    }
}

function Example() {
    const [state, dispatch] = useReducer(reducer, initialState);

    useEffect(() => {
        async function fetchCsrfToken() {
            const response = await fetch('/csrf-token');
            const data = await response.json();
            dispatch({ type: 'SET_CSRF_TOKEN', payload: data.csrfToken });
        }
        fetchCsrfToken();
    }, []);

    const handleFileChange = (e) => {
        dispatch({ type: 'SET_FILE', payload: e.target.files[0] });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        dispatch({ type: 'SET_UPLOADING', payload: true });

        const formData = new FormData();
        formData.append('_token', state.csrfToken);
        formData.append('file', state.file);

        try {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/upload', true);

            // Configurando o evento de progresso
            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    const percentCompleted = Math.round((event.loaded * 100) / event.total);
                    dispatch({ type: 'SET_UPLOAD_PROGRESS', payload: percentCompleted });
                }
            };

            xhr.onload = async () => {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    dispatch({ type: 'SET_UPLOAD_MESSAGE', payload: 'Arquivo enviado com sucesso.' });
                    dispatch({ type: 'SET_UPDATE_FILES', payload: true });
                } else {
                    dispatch({ type: 'SET_UPLOAD_MESSAGE', payload: 'Erro ao enviar arquivo.' });
                }
                dispatch({ type: 'SET_UPLOADING', payload: false });
            };

            xhr.send(formData);
        } catch (error) {
            console.error('Erro ao enviar arquivo:', error);
            dispatch({ type: 'SET_UPLOAD_MESSAGE', payload: 'Erro ao enviar arquivo.' });
            dispatch({ type: 'SET_UPLOADING', payload: false });
        }
    };

    return (
        <div className="container">
            <div className="row">
                <div className="col-md-6 offset-md-3">
                    <br />
                    <h2 className="text-center">Carregar Arquivos .CSV</h2>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <input type="file" className="form-control" onChange={handleFileChange} />
                        </div>
                        <div className="text-center"> {/* Esta div é adicionada */}
                            <button type="submit" className="btn btn-primary btn-block">Carregar Arquivo</button>
                        </div>
                    </form>
                    {state.uploading && <PreLoader progress={state.uploadProgress} />}
                    {state.uploadMessage && <p>{state.uploadMessage}</p>}
                    <FileList files={state.files} updateFiles={state.updateFiles} />
                    <br></br>
                </div>
            </div>
            <div className="row">
                <div className="col-md-12 text-center">
                    <h6>Autor: Maurício Pacheco</h6>
                </div>
            </div>
        </div>
    );
}

export default Example;
