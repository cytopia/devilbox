---
name: "\U0001F41B Bug report"
about: Something is not working? Create a report to help us improve
title: ''
labels: bug
assignees: ''

---

<!---
1. Verify first that your issue/request is not already reported on GitHub.

2. Verify that your question is not covered in the docs: https://devilbox.readthedocs.io

3. PLEASE FILL OUT ALL REQUIRED INFORMATION BELOW! Otherwise it might take more time to properly handle this bug report.
-->


### ISSUE TYPE
<!-- DO NOT CHANGE THIS -->
 - Bug Report
<!-- DO NOT CHANGE THIS -->


### Checklist
<!-- ENSURE YOU HAVE DONE THE FOLLOWING -->
* [ ] `.env` file is attached
* [ ] `./check-config.sh` output is added below
* [ ] `docker-compose logs` output is added below
* [ ] `docker-compose.override.yml` is attached (if exists)
* [ ] Custom configs from `cfg/` dir are attached (if exist)
* [ ] I've looked through the docs: https://devilbox.readthedocs.io/en/latest/
* [ ] I've looked through existing issues: https://github.com/cytopia/devilbox/issues
* [ ] I've read troubleshooting: https://devilbox.readthedocs.io/en/latest/support/troubleshooting.html


### OS / ENVIRONMENT
<!-- COMPLETE ALL 6 BULLET POINTS BELOW: -->
1. Host operating system and version:
2. (Windows only) Native Docker or Docker Toolbox:
3. Docker version:
4. Docker Compose version:
5. (Linux) Is SELinux enabled?:
6. What git commit hash are you on?:


### SUMMARY
<!-- Explain the problem briefly -->


### STEPS TO REPRODUCE
<!-- Show exactly how to reproduce the problem -->
<!-- Make this as detailed as possible and be sure that others can fully reproduce this issue -->


### EXPECTED BEHAVIOUR
<!-- What is the expected behaviour? -->


### ACTUAL BEHAVIOUR
<!-- What is the actual behaviour? -->


### OTHER INFORMATION

#### Start command
<!-- Add the command you have used to start the devilbox -->
```
$ docker-compose up...
```

#### Outputs
<!-- 1/2 Add the output from ./check-config.sh -->
```bash
$ ./check-config.sh

<<< REPLACE THIS LINE WITH OUTPUT FROM ABOVE COMMAND >>>
```

<!-- 2/2 Add the output from docker-compose logs -->
```bash
$ docker-compose logs

<<< REPLACE THIS LINE WITH OUTPUT FROM ABOVE COMMAND >>>
```
