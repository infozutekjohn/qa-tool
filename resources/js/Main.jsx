import React, { useEffect, useState } from "react";
import "../css/app.css";
import GeneralDetails from "./components/GeneralDetails";
import Parameters from "./components/Parameters";
import TestFlow from "./components/TestFlow";

const Main = () => {
    const [username, setUsername] = useState("");
    const [token, setToken] = useState("");
    const [endpoint, setEndpoint] = useState("https://api-uat.agmidway.net/");
    const [loading, setLoading] = useState(false);
    const [runs, setRuns] = useState([]);

    const [casinoGameCode, setCasinoGameCode] = useState("");
    const [liveGameCode, setLiveGameCode] = useState("");
    const [crossGameCode, setCrossGameCode] = useState("");
    const [launchAlias, setLaunchAlias] = useState("");
    const [betPrimary, setBetPrimary] = useState("");
    const [betSecondary, setBetSecondary] = useState("");
    const [winPrimary, setWinPrimary] = useState("");
    const [remoteBonusCodePrimary, setRemoteBonusCodePrimary] = useState("");
    const [bonusInstanceCodePrimary, setBonusInstanceCodePrimary] =
        useState("");
    const [bonusTemplatePrimary, setBonusTemplatePrimary] = useState("");
    const [remoteBonusCodeSecondary, setRemoteBonusCodeSecondary] =
        useState("");
    const [bonusInstanceCodeSecondary, setBonusInstanceCodeSecondary] =
        useState("");
    const [bonusTemplateSecondary, setBonusTemplateSecondary] = useState("");
    const [jackpot, setJackpot] = useState("yes");

    const [testGroups, setTestGroups] = useState({
        login: true,
        casino: false,
        live: false,
        bonus: false,
        error: false,
        gameslink: false,
        logout: false,
    });

    const [runState, setRunState] = useState({
        status: "idle",
        id: null,
        message: "",
    });

    const fetchRuns = async () => {
        const res = await fetch("/api/test-runs");
        const data = await res.json();
        setRuns(data);
    };

    useEffect(() => {
        fetchRuns();
    }, []);

    const pollRun = async (id) => {
        const res = await fetch(`/api/test-runs/${id}`);
        return await res.json();
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setRunState({
            status: "running",
            id: null,
            message: "Running tests...",
        });

        const payload = {
            username,
            token,
            endpoint,
            testGroups,

            casinoGameCode,
            liveGameCode,
            crossGameCode,
            launchAlias,
            betPrimary,
            betSecondary,
            winPrimary,
            remoteBonusCodePrimary,
            bonusInstanceCodePrimary,
            bonusTemplatePrimary,
            remoteBonusCodeSecondary,
            bonusInstanceCodeSecondary,
            bonusTemplateSecondary,
            jackpot,
        };

        try {
            const res = await fetch("/api/test-runs", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload),
            });

            const created = await res.json();
            const runId = created.id;

            setRunState({
                status: "running",
                id: runId,
                message: `Run #${runId} started...`,
            });

            const startedAt = Date.now();
            while (true) {
                const run = await pollRun(runId);

                if (run.status === "success") {
                    setRunState({
                        status: "success",
                        id: runId,
                        message: "Report generated successfully.",
                    });
                    break;
                }

                if (run.status === "failed") {
                    // setRunState({
                    //     status: "failed",
                    //     id: runId,
                    //     message: run.error_message || "Run failed.",
                    // });

                    setRunState({
                        status: "failed",
                        id: runId,
                        message:
                            run.error_message || "Report generation failed.",
                    });
                    break;
                }

                if (Date.now() - startedAt > 15 * 60 * 1000) {
                    // setRunState({
                    //     status: "failed",
                    //     id: runId,
                    //     message: "Timed out waiting for report.",
                    // });

                    setRunState({
                        status: "failed",
                        id: runId,
                        message:
                            run.error_message || "Report generation failed.",
                    });
                    break;
                }

                await new Promise((r) => setTimeout(r, 3000));
            }

            await fetchRuns();
        } catch (err) {
            setRunState({
                status: "failed",
                id: null,
                message: "Request failed.",
            });
        } finally {
            setLoading(false);
        }
    };

    // const statusBadge = (status) => {
    //     if (status === "success")
    //         return <span className="badge text-bg-success">COMPLETED</span>;
    //     if (status === "failed")
    //         return <span className="badge text-bg-danger">FAILED</span>;
    //     return <span className="badge text-bg-secondary">RUNNING</span>;
    // };

    const reportBadge = (run) => {
        if (run.status === "running")
            return <span className="badge text-bg-secondary">GENERATING</span>;
        if (run.status === "failed")
            return <span className="badge text-bg-danger">REPORT FAILED</span>;
        return <span className="badge text-bg-success">READY</span>;
    };

    const passPercent = (run) => {
        if (run.phpunit_exit === 0) return "100%";
        if (run.phpunit_exit === 1) return "—"; // until you compute real %
        return "—";
    };

    return (
        <div className="container-fluid my-4">
            <h3 className="mb-3">API Test Runner</h3>

            {/* CONFIGURATION PANEL */}
            <div className="card mb-4">
                <div className="card-header fw-bold">Configuration</div>
                <div className="card-body">
                    <form onSubmit={handleSubmit}>
                        <div className="row g-3">
                            {/* LEFT: General + Collections */}
                            <div className="col-lg-6">
                                <GeneralDetails
                                    username={username}
                                    handleUsername={setUsername}
                                    token={token}
                                    handleToken={setToken}
                                    endpoint={endpoint}
                                    handleEndpoint={(e) =>
                                        setEndpoint(e.target.value)
                                    }
                                />

                                <TestFlow
                                    testGroups={testGroups}
                                    setTestGroups={setTestGroups}
                                />
                            </div>

                            {/* RIGHT: Test Variables */}
                            <div className="col-lg-6">
                                <div className="card h-100">
                                    <div className="card-header fw-bold d-flex justify-content-between align-items-center">
                                        <span>Test Variables</span>
                                    </div>
                                    <div className="card-body">
                                        <Parameters
                                            casinoGameCode={casinoGameCode}
                                            handleCasinoGameCode={
                                                setCasinoGameCode
                                            }
                                            liveGameCode={liveGameCode}
                                            handleLiveGameCode={setLiveGameCode}
                                            crossGameCode={crossGameCode}
                                            handleCrossGameCode={
                                                setCrossGameCode
                                            }
                                            launchAlias={launchAlias}
                                            handleLaunchAlias={setLaunchAlias}
                                            betPrimary={betPrimary}
                                            handleBetPrimary={setBetPrimary}
                                            betSecondary={betSecondary}
                                            handleBetSecondary={setBetSecondary}
                                            winPrimary={winPrimary}
                                            handleWinPrimary={setWinPrimary}
                                            remoteBonusCodePrimary={
                                                remoteBonusCodePrimary
                                            }
                                            handleRemoteBonusCodePrimary={
                                                setRemoteBonusCodePrimary
                                            }
                                            bonusInstanceCodePrimary={
                                                bonusInstanceCodePrimary
                                            }
                                            handleBonusInstanceCodePrimary={
                                                setBonusInstanceCodePrimary
                                            }
                                            bonusTemplatePrimary={
                                                bonusTemplatePrimary
                                            }
                                            handleBonusTemplatePrimary={
                                                setBonusTemplatePrimary
                                            }
                                            remoteBonusCodeSecondary={
                                                remoteBonusCodeSecondary
                                            }
                                            handleRemoteBonusCodeSecondary={
                                                setRemoteBonusCodeSecondary
                                            }
                                            bonusInstanceCodeSecondary={
                                                bonusInstanceCodeSecondary
                                            }
                                            handleBonusInstanceCodeSecondary={
                                                setBonusInstanceCodeSecondary
                                            }
                                            bonusTemplateSecondary={
                                                bonusTemplateSecondary
                                            }
                                            handleBonusTemplateSecondary={
                                                setBonusTemplateSecondary
                                            }
                                            jackpot={jackpot}
                                            handleJackpot={(e) =>
                                                setJackpot(e.target.value)
                                            }
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* status alert */}
                        {runState.status !== "idle" && (
                            <div
                                className={`alert mt-3 mb-0 ${
                                    runState.status === "running"
                                        ? "alert-info"
                                        : runState.status === "success"
                                        ? "alert-success"
                                        : "alert-danger"
                                }`}
                            >
                                {runState.message}
                            </div>
                        )}

                        {/* Run button (like screenshot: centered-ish under config) */}
                        <div className="d-flex justify-content-center mt-3">
                            <button
                                className="btn btn-primary px-4"
                                disabled={loading}
                            >
                                {loading ? "Running…" : "Run Tests"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {/* TEST RESULTS */}
            <div className="card">
                <div className="card-header fw-bold">Test results</div>
                <div className="card-body">
                    {runs.length === 0 && (
                        <p className="text-muted mb-0">No runs yet.</p>
                    )}

                    {runs.length > 0 && (
                        <div className="table-responsive">
                            <table className="table table-bordered table-sm align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th style={{ width: 70 }}>ID</th>
                                        <th style={{ width: 150 }}>REPORT</th>
                                        {/* <th style={{ width: 90 }}>EXIT</th> */}
                                        <th style={{ width: 110 }}>PASS %</th>
                                        <th>USERNAME</th>
                                        <th style={{ width: 260 }}>
                                            TOKEN USED
                                        </th>
                                        <th>PROJECT ID</th>
                                        <th style={{ width: 220 }}>CREATED</th>
                                        <th style={{ width: 110 }}>OPEN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {runs.map((run) => (
                                        <tr key={run.id}>
                                            <td>{run.id}</td>
                                            <td>{reportBadge(run)}</td>
                                            {/* <td>{run.phpunit_exit ?? "-"}</td> */}
                                            <td>{passPercent(run)}</td>
                                            <td>{run.username}</td>
                                            <td className="text-break">
                                                {run.token_used ?? "-"}
                                            </td>
                                            <td>{run.project_code ?? "-"}</td>
                                            <td>{run.created_at}</td>
                                            <td>
                                                <a
                                                    href={run.report_url || "#"}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className={`btn btn-sm ${
                                                        run.report_url
                                                            ? "btn-outline-primary"
                                                            : "btn-outline-secondary disabled"
                                                    }`}
                                                >
                                                    Open
                                                </a>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default Main;
