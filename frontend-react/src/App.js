import {Header} from "./layout/Header/Header";
import {Container} from "./layout/container/Container";

function App() {
    return (
        <>
            <Header />
            <div className="content">
                <Container>
                    <h1>Платформа реалізації ідей для покращення твого міста</h1>
                </Container>
            </div>
        </>
    );
}

export default App;
